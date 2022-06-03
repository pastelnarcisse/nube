<?php
namespace App\Models;

/**
 * Name:    Statistics Model
 *
 * Created:  10.11.2021
 *
 * Description:  Modelo para los datos de estadística.
 *               Sus modificaciones son constantes
 *
 * Requirements: PHP 7.2 or above
 *
 * @package    NubeNarcisse 1.0
 * @author     Erick Aguirre Mtz <chununo@gmail.com>
 * @filesource
 */

use \CodeIgniter\Database\ConnectionInterface;

/**
 * Class StatisticsModel
 *
 * @property Statistics $statistics The Statistics library
 */
class StatisticsModel
{
	/**
	 * Max cookie lifetime constant
	 */
	const MAX_COOKIE_LIFETIME = 63072000; // 2 years = 60*60*24*365*2 = 63072000 seconds;

	/**
	 * Max password size constant
	 */
	const MAX_PASSWORD_SIZE_BYTES = 4096;

	/**
	 * IonAuth config
	 *
	 * @var Config\IonAuth
	 */
	protected $config;

	/**
	 * CodeIgniter session
	 *
	 * @var \CodeIgniter\Session\Session
	 */
	protected $session;

	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 */
	public $tables = [];

	/**
	 * Response
	 *
	 * @var \CodeIgniter\Database\ResultInterface
	 */
	protected $response = null;

	/**
	 * Message (uses lang file)
	 *
	 * @var string
	 */
	protected $messages = '';

	/**
	 * Error message (uses lang file)
	 *
	 * @var string
	 */
	protected $errors = '';

	/**
	 * Message templates (single, list).
	 *
	 * @var array
	 */
	protected $messageTemplates = [];

	/**
	 * Database object
	 *
	 * @var \CodeIgniter\Database\BaseConnection
	 */
	protected $db;
	

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->config = config('NubeNarcisse');
		helper(['cookie', 'date']);
		$this->session = session();

		// initialize the database
		if (empty($this->config->databaseGroupName))
		{
			// By default, use CI's db that should be already loaded
			$this->db = \Config\Database::connect();
		}
		else
		{
			// For specific group name, open a new specific connection
			$this->db = \Config\Database::connect($this->config->databaseGroupName);
		}

		// initialize db tables data
		$this->tables = $this->config->tables;

		// initialize data
		$this->join           = $this->config->join;

		// load the messages template from the config file
		$this->messagesTemplates = $this->config->templates['messages'];

		// initialize our hooks object
		$this->ionHooks = new \stdClass();

		$this->triggerEvents('model_constructor');


		$this->response['error'] = $this->errors;
		$this->response['message'] = $this->messages;
		
	}

	/**
	 * Getter to the DB connection used by Nube Narcisse
	 * May prove useful for debugging
	 *
	 * @return object
	 */
	public function db()
	{
		return $this->db;
	}

	/**
	 * Call Additional functions to run that were registered with setHook().
	 *
	 * @param string|array $events Event(s)
	 *
	 * @return void
	 */
	public function triggerEvents($events): void
	{
		if (is_array($events) && ! empty($events))
		{
			foreach ($events as $event)
			{
				$this->triggerEvents($event);
			}
		}
		else
		{
			if (isset($this->ionHooks->$events) && ! empty($this->ionHooks->$events))
			{
				foreach ($this->ionHooks->$events as $name => $hook)
				{
					$this->callHook($events, $name);
				}
			}
		}
	}


	
	/**
	 * Dame ventas del día
	 *
	 * @param string $date Fecha
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getSalesByDay(string $date)
	{
		$this->triggerEvents('setSalesByDate');

		$builder = $this->db->table($this->tables['venta_diaria']);
		$query   = $builder
					   ->where('fecha', $date)
					   ->limit(1)
					   ->get()->getRow();

		if (empty($query))
		{
			$this->triggerEvents(['setSalesByDate', 'no_data']);
			$this->response['error'] = 'Sin datos para la fecha '.$date;
			return false;
		}

		$this->response['data'] = $query; 
		$this->response['message'] = 'Hay ventas al dia de '.$date;
		return $this->response;
	}

	/**
	 * Dame ventas del día por sucursal
	 *
	 * @param string $date Fecha
	 * @param array $store_id arreglos del id de sucursal
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getSalesStoreByDay(string $date, array $store_id = [])
	{
		$this->triggerEvents('getSalesStoreByDay');

		if (empty($store_id)) {
			$this->response['data']  = [];
			$this->response['error'] = 'No tienes sucursales';
			$this->response['status'] = 450;
			return $this->response;
		}

		$builder = $this->db->table($this->tables['venta_diaria_sucursal']);

		

		$builder->whereIn('store_id',$store_id);
		$builder->where('fecha', $date);
		$this->response['data']  = $builder->get()->getResult();
		$this->response['columns'] =  $builder->get()->getFieldNames();

		if (empty($this->response['data']))
		{
			$this->triggerEvents(['getSalesStoreByDay', 'no_data']);
			$this->response['error'] = 'Sin datos';
			$this->response['status'] = 451;

			return $this->response;
		}
		$this->response['message'] = 'Hay ventas al dia de '.$date;
		$this->response['status'] = 200;

		return $this->response;
	}

	/**
	 * Dame los inventarios del dia
	 *
	 * @param string $date Fecha
	 * @param array $store_id arreglos del id de sucursal
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getstockCategoryStore(string $date, array $store_id = [])
	{
		$this->triggerEvents('getstockCategory');

		$this->response['data']  = [];
		if (empty($store_id)) {
			$this->response['error'] = 'No tienes sucursales';
			return $this->response;
		}

		$builder = $this->db->table($this->tables['stockCategory']);

		
		$builder->whereIn('suc_id',$store_id);
		$builder->where('fecha', $date);
		$data  = $builder->get()->getResult();

		if (empty($data))
		{	
			$this->triggerEvents(['getSalesStoreByDay', 'no_data']);
			$this->response['error'] = 'Sin datos';
			return $this->response;
		}
		$this->response['data'] = $data;
		$this->response['message'] = 'Hay ventas al dia de '.$date;

		return $this->response;
	}



}
