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
class HistorialModel
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
	 * Dame los departamentos
	 *
	 * @param array $status arreglo de estatos, 0 y 1
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getStoreByStoreCategoyIDs($store_ids, $category_ids, $startDate, $finishDate)
	{
		$this->triggerEvents('getStoreByStoreCategoyIDs');

		$this->response['columns'] = array();
		$this->response['nomColumns'] = 0;
		$this->response['rows'] = array(); 
		$this->response['nomRows'] = 0;
		$this->response['message'] .= '| Entro a la funcion |';

		$builder = $this->db->table($this->tables['stock']);
		$builder->where("date(fecha) between '$startDate' AND '$finishDate' ");
		$builder->whereIn('suc_id',$store_ids);
		$builder->whereIn('cat_id_local',$category_ids);
		
		$query = $builder->get()->getResult('array');

		if (empty($query))
		{
			$this->triggerEvents(['getDepartment-model', 'no_data']);
			$this->response['builder'] = $builder->get();
			$this->response['error'] = 'no data';
			$this->response['status'] = 400;
			return $this->response;
		}

		$this->response['columns'] = array_keys((array)$query[0]);
		$this->response['nomColumns'] = count($this->response['columns']);
		$this->response['rows'] = $query; 
		$this->response['nomRows'] = count($query);
		$this->response['message'] = '| Hay existencias |';
		$this->response['status'] = 200;
		return $this->response;
	}

	





}
