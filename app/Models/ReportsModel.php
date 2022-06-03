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
class ReportsModel
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
	 * Dame los departamentos
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
	 * Dame ventas del día
	 *
	 * @param string $date Fecha
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getSalesGeneral(object $input)
	{
		$this->triggerEvents('getSalesGeneral');

		$this->response['columns'] = array();
		$this->response['nomColumns'] = 0;
		$this->response['rows'] = array(); 
		$this->response['nomRows'] = 0;
		$this->response['message'] .= '| Entro a la funcion |';

		$builder = $this->db->table($this->tables['sale_general']);
		
		$builder->where('datePay BETWEEN "'.$input->startDate.' '.$input->startTime.'" AND "'.$input->finishDate.' '.$input->finishTime.'"', NULL, false);
		$builder->select('DATE(datePay) FECHA');
		$builder->select('TIME(datePay) HORA');
		$builder->select('ticket TICKET');

		$builder->whereIn('storeID',$input->suc_ids);
		$builder->select('storeName SUCURSAL');

		$builder->select($input->payment);

		$builder->select('TOTAL');

		$builder->where('credit', 0);

		$builder->whereIn('statusID',$input->status);
		if (count($input->status) > 1) { $builder->select('statusName ESTADO'); }

		$builder->whereIn('movementID', $input->movement);
		if (count($input->movement) > 1) { $builder->select('movementName MOVIMIENTO'); }

		$builder->orderBy($input->order, $input->by);

		$query = $builder->get()->getResult('array');

		if (empty($query))
		{
			$this->triggerEvents(['setSalesByDate', 'no_data']);
			$this->response['builder'] = $builder->get();
			$this->response['error'] = 'no data';
			return $this->response;
		}

		$this->response['columns'] = array_keys((array)$query[0]);
		$this->response['nomColumns'] = count($this->response['columns']);
		$this->response['rows'] = $query; 
		$this->response['nomRows'] = count($query);
		$this->response['message'] = '| Hay ventas al dia de |';
		return $this->response;
	}


	/**
	 * Dame ventas del día
	 *
	 * @param string $date Fecha
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getSalesProducts(object $input)
	{
		$this->triggerEvents('getSalesProducts');

		$this->response['columns'] = array();
		$this->response['nomColumns'] = 0;
		$this->response['rows'] = array(); 
		$this->response['nomRows'] = 0;
		$this->response['message'] .= '| Entro a la funcion |';

		$builder = $this->db->table($this->tables['sale_products']);
		
		$builder->where('saleDate BETWEEN "'.$input->startDate.' '.$input->startTime.'" AND "'.$input->finishDate.' '.$input->finishTime.'"', NULL, false);
		$builder->select('DATE(saleDate) FECHA');
		$builder->select('TIME(saleDate) HORA');
		$builder->select('saleTicket TICKET');

		$builder->whereIn('storeID',$input->suc_ids);
		$builder->select('storeName SUCURSAL');

		$builder->select('productName ARTICULO');
		$builder->select('productAmount CANTIDAD');
		$builder->select('productUnit UNIDAD');
		$builder->select('productCategoryName CATEGORIA');

		$builder->select($input->selectProduct);

		$builder->whereIn('productCategoryID',$input->categorys);

		$builder->whereIn('saleStatus',$input->status);
		if (count($input->status) > 1) { $builder->select('IF(saleStatus = 1, "Vigente", "Cancelado") ESTADO'); }

		$builder->orderBy($input->order, $input->by);

		$query = $builder->get()->getResult('array');

		if (empty($query))
		{
			$this->triggerEvents(['setSalesProducts', 'no_data']);
			$this->response['builder'] = $builder->get();
			$this->response['error'] = 'no data';
			return $this->response;
		}

		$this->response['columns'] = array_keys((array)$query[0]);
		$this->response['nomColumns'] = count($this->response['columns']);
		$this->response['rows'] = $query; 
		$this->response['nomRows'] = count($query);
		$this->response['message'] = '| Hay ventas al dia de |';
		return $this->response;
	}
	
	
	/**
	 * Dame inventarios
	 *
	 * @param array $input conjunto de datos
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function getProductsStock(object $input)
	{
		$this->triggerEvents('getProductsStock');

		$this->response['columns'] = array();
		$this->response['nomColumns'] = 0;
		$this->response['rows'] = array(); 
		$this->response['nomRows'] = 0;
		$this->response['message'] .= '| Entro a la funcion |';

		$builder = $this->db->table('v_inventario');
		
		$builder->whereIn('cat_id_local',$input->categorys);
		$builder->select('cat_nombre_local CATEGORIA');
		$builder->select('clave CLAVE');
		$builder->select('descripcion ARTICULO');
		$builder->select('existencia EXISTENCIA');
		$builder->select('unidad UNIDAD');
		$builder->whereIn('suc_id',$input->suc_ids);
		$builder->select('store_name SUCURSAL');


		// $builder->whereIn('statusID',$input->status);
		// if (count($input->status) > 1) { $builder->select('statusName ESTADO'); }

		// $builder->whereIn('movementID', $input->movement);
		// if (count($input->movement) > 1) { $builder->select('movementName MOVIMIENTO'); }

		$builder->orderBy($input->order, $input->by);

		$query = $builder->get()->getResult('array');

		if (empty($query))
		{
			$this->triggerEvents(['getProductsStock', 'no_data']);
			$this->response['builder'] = $builder->get();
			$this->response['error'] = 'no data';
			return $this->response;
		}

		$this->response['columns'] = array_keys((array)$query[0]);
		$this->response['nomColumns'] = count($this->response['columns']);
		$this->response['rows'] = $query; 
		$this->response['nomRows'] = count($query);
		$this->response['message'] = '| Hay existencias |';
		return $this->response;
	}





}
