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
class StructureModel
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
	 * Database object
	 *
	 * @var \CodeIgniter\Database\BaseConnection
	 */
	protected $db_cloud;
	

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
			$this->db_cloud = \Config\Database::connect($this->config->databaseGroupCloud);
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
	public function getDepartment($status)
	{
		$this->triggerEvents('getDepartment');

		$this->response['columns'] = array();
		$this->response['nomColumns'] = 0;
		$this->response['rows'] = array(); 
		$this->response['nomRows'] = 0;
		$this->response['message'] .= '| Entro a la funcion |';

		$builder = $this->db->table($this->tables['department']);
		$builder->whereIn('status',$status);
		$query = $builder->get()->getResult('array');

		if (empty($query))
		{
			$this->triggerEvents(['getDepartment-model', 'no_data']);
			$this->response['builder'] = $builder->get();
			$this->response['error'] = 'no data';
			return $this->response;
		}

		$this->response['columns'] = array_keys((array)$query[0]);
		$this->response['nomColumns'] = count($this->response['columns']);
		$this->response['rows'] = $query; 
		$this->response['nomRows'] = count($query);
		$this->response['message'] = '| Hay departamentos |';
		return $this->response;
	}

	/**
	 * Dame las categorias
	 *
	 * @param array $status arreglo de estatos, 0 y 1
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getCategory($status)
	{
		$this->triggerEvents('getCategory');

		$this->response['columns'] = array();
		$this->response['nomColumns'] = 0;
		$this->response['rows'] = array(); 
		$this->response['nomRows'] = 0;
		$this->response['message'] .= '| Entro a la funcion |';

		$builder = $this->db->table($this->tables['category'].' cat');
		$builder->whereIn('cat.status',$status);
		$builder->whereIn('dep.status',$status);
		$builder->join($this->tables['department'].' dep', 'dep.dep_id = cat.dep_id', 'left');
		$builder->select('cat.*');
		$builder->select('dep.nombre depNombre');
		$builder->select('dep.status depStatus');
		$query = $builder->get()->getResult('array');

		if (empty($query))
		{
			$this->triggerEvents(['getDepartment-model', 'no_data']);
			$this->response['builder'] = $builder->get();
			$this->response['error'] = 'no data';
			return $this->response;
		}

		$this->response['columns'] = array_keys((array)$query[0]);
		$this->response['nomColumns'] = count($this->response['columns']);
		$this->response['rows'] = $query; 
		$this->response['nomRows'] = count($query);
		$this->response['message'] = '| Hay categorias |';
		return $this->response;
	}

	/**
	 * Dame las categorias
	 *
	 * @param array $status arreglo de estatos, 0 y 1
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getCategoryIDs($status)
	{
		$this->triggerEvents('getCategory');

		$this->response['columns'] = array();
		$this->response['nomColumns'] = 0;
		$this->response['rows'] = array(); 
		$this->response['nomRows'] = 0;
		$this->response['message'] .= '| Entro a la funcion |';

		$builder = $this->db->table($this->tables['category'].' cat');
		$builder->whereIn('cat.status',$status);
		$builder->whereIn('dep.status',$status);
		$builder->join($this->tables['department'].' dep', 'dep.dep_id = cat.dep_id', 'left');
		$builder->select('cat.cat_id');
		$query = $builder->get()->getResult('array');

		if (empty($query))
		{
			$this->triggerEvents(['getDepartment-model', 'no_data']);
			$this->response['builder'] = $builder->get();
			$this->response['error'] = 'no data';
			return $this->response;
		}

		foreach ($query as $key => $value) {
			$row[] = $value['cat_id'];
		}

		$this->response['columns'] = array_keys((array)$query[0]);
		$this->response['nomColumns'] = count($this->response['columns']);
		$this->response['rows'] = $row; 
		$this->response['nomRows'] = count($query);
		$this->response['message'] = '| Hay categorias IDS |';
		return $this->response;
	}

	/**
	 * Dame las categorias
	 *
	 * @param array $status arreglo de estatos, 0 y 1
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getStores($store_ids)
	{
		$this->triggerEvents('getStores');

		$this->response['columns'] = array();
		$this->response['nomColumns'] = 0;
		$this->response['rows'] = array(); 
		$this->response['nomRows'] = 0;
		$this->response['message'] .= '| Entro a la funcion |';

		$builder = $this->db_cloud->table($this->tables['store'].' st');
		$builder->whereIn('st.store_id',$store_ids);
		$query = $builder->get()->getResult('array');

		if (empty($query))
		{
			$this->triggerEvents(['getStores-model', 'no_data']);
			$this->response['builder'] = $builder->get();
			$this->response['error'] = 'no data';
			return $this->response;
		}

		$this->response['columns'] = array_keys((array)$query[0]);
		$this->response['nomColumns'] = count($this->response['columns']);
		$this->response['rows'] = $query; 
		$this->response['nomRows'] = count($query);
		$this->response['message'] = '| Hay sucursales ';
		return $this->response;
	}



}
