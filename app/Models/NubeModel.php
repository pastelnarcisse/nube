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
class NubeModel
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
		if (empty($this->config->databaseGroupCloud))
		{
			// By default, use CI's db that should be already loaded
			$this->db = \Config\Database::connect();
		}
		else
		{
			// For specific group name, open a new specific connection
			$this->db = \Config\Database::connect($this->config->databaseGroupCloud);
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
		$this->response['status'] = 0;
		$this->response['objects'] = false;
		$this->response['object'] = false;
		
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
	 * Guarda inventario general
	 *
	 * @param array $data arreglo con structura de la base de datos
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function saveInventaryAdjustment(array $data = [])	{
		$this->triggerEvents('saveInventaryAdjustment');

		if (empty($data)) {
			$this->error('No se envio nada');
			$this->status(401);
			return $this->response;
		}

		try {

			$builder = $this->db->table('inventory_adjustment');
			$builder->insert($data);

			$insert_id = $this->db->insertID();

			$this->message('EXITOSO') ;
			$this->object($insert_id);
			$this->status(200);
			return $this->response;
			
		} catch (Exception $e) {
			$this->error($e);
			$this->status(400);
			return $this->response;
		}

	}

	/**
	 * Guarda inventario detallado
	 *
	 * @param array $data arreglo con structura de la base de datos
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function saveInventaryAdjustmentDetail(array $data = [])	{
		$this->triggerEvents('saveInventaryAdjustmentDetail');

		if (empty($data)) {
			$this->error('No se envio nada');
			$this->status(401);
			return $this->response;
		}

		try {

			$builder = $this->db->table('inventory_adjustment_detail');
			$builder->insert($data);

			$insert_id = $this->db->insertID();

			$this->message('EXITOSO') ;
			$this->object($insert_id);
			$this->status(200);
			return $this->response;
			
		} catch (Exception $e) {
			$this->error($e);
			$this->status(400);
			return $this->response;
		}

	}

	public function error($error){
		$this->response['error'] = $error;
	}

	public function status($status = 200){
		$this->response['status'] = $status;
	}

	public function message($message){
		$this->response['message'] = $message;
	}


	public function object($object){
		$this->response['object'] = $object;
	}

	public function objects($object){
		$this->response['objects'] = $objects;
	}
}
