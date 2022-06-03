<?php
namespace App\Libraries;

/**
 * Name:    Statistics
 *
 * Created:  10.11.2021
 *
 * Description:  Modified auth system based on redux_auth with extensive customization.
 *               This is basically what Redux Auth 2 should be.
 * Original Author name has been kept but that does not mean that the method has not been modified.
 *
 * Requirements: PHP7.2 or above
 *
 * @package    CodeIgniter-Ion-Auth
 * @author     Ben Edmunds <ben.edmunds@gmail.com>
 * @author     Phil Sturgeon
 * @author     Benoit VRIGNAUD <benoit.vrignaud@zaclys.net>
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       http://github.com/benedmunds/CodeIgniter-Ion-Auth
 * @filesource
 */

/**
 * This class is the IonAuth library.
 */
class Statistics
{
	/**
	 * Configuration
	 *
	 * @var \IonAuth\Config\IonAuth
	 */
	protected $config;

	/**
	 * IonAuth model
	 *
	 * @var \IonAuth\Models\StatisticsModel
	 */
	protected $statisticsModel;

	/**
	 * IonAuth model
	 *
	 * @var \IonAuth\Models\StructureModel
	 */
	protected $structureModel;


	/**
	 * __construct
	 *
	 * @author Ben
	 */
	public function __construct()
	{
		
		$this->config = config('NubeNarcisse');

		helper('cookie');

		$this->session = session();

		$this->statisticsModel = new \App\Models\StatisticsModel();

		$this->statisticsModel->triggerEvents('library_constructor');

		$this->structureModel = new \App\Models\StructureModel();

		$this->structureModel->triggerEvents('library_constructor');
	}

	
	/**
	 * Ventas al dia
	 *
	 * @param string $date Fecha que se quiere saber numero de ventas
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function salesByDate(string $date = ""){

		$this->statisticsModel->triggerEvents('salesByDate');

		if ($date == "") {
			$date = date('Y-m-d');
		}

		return $this->statisticsModel->getSalesByDay($date);
		
	}

	/**
	 * Cuenta ventas al dia
	 *
	 * @param string $date Fecha que se quiere saber numero de ventas
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function countSales(string $date = "", array $store_id = []){

		$tickets = 0;
		$result = $this->salesStoreByDate($date, $store_id);

		foreach ($result['data'] as $res) {
			$tickets += intval($res->tickets);
		}

		$return['result'] = $tickets;
		$return['message'] = $result['message'];
		$return['error'] = $result['error'];

		return $return;
		
	}

	/**
	 * SUMA ventas al dia
	 *
	 * @param string $date Fecha que se quiere saber numero de ventas
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function sumSales(string $date = "", array $store_id = []){

		$total = 0;
		$result = $this->salesStoreByDate($date, $store_id);

		foreach ($result['data'] as $res) {
			$total += floatval($res->total);
		}

		$return['result'] = $total;
		$return['message'] = $result['message'];
		$return['error'] = $result['error'];

		return $return;
		
	}

	/**
	 * Ventas al dia por sucursal
	 *
	 * @param string $date Fecha que se quiere saber numero de ventas
	 * @param string $date Fecha que se quiere saber numero de ventas
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function salesStoreByDate(string $date = "", array $store_id = []){

		$this->statisticsModel->triggerEvents('salesByDate');

		if ($date == "") {
			$date = date('Y-m-d');
		}

		$result = $this->statisticsModel->getSalesStoreByDay($date, $store_id);

		// SIN DATOS
		if ($result['status'] == 451 && !empty($store_id)) {

			$stores = $this->structureModel->getStores($store_id);

			$data = array();	

			foreach ($stores['rows'] as $store) {
				
				foreach ($result['columns'] as $column) {
					
					$data[$column] = 0;

					if ($column == 'FECHA') {
						$data[$column] = $date; 
					}
					if ($column == 'sucursal') {
						$data[$column] = $store['store_name'];
					}
					
				}

				$result['data'][] = (object)$data;

			}

		}

		return $result;
		
	}

	/**
	 * Stock por sucursal
	 *
	 * @param string $date Fecha para checar inventarios
	 * @param string $store_id Entre tiendas
	 *
	 * @return array|boolean
	 * @author Chununo
	 */
	public function stockCategory(string $date = "", array $store_id = []){

		$this->statisticsModel->triggerEvents('stockCategory');

		if ($date == "") {
			$date = date('Y-m-d');
		}

		$result = $this->statisticsModel->getstockCategoryStore($date, $store_id);

		if (empty($result['data'])) {
			return $result;
		}

		foreach ($result['data'] as $value) {
			if (!isset($result['data_category'][$value->categoria])) { 
				$result['data_category'][$value->categoria] = $value;
			}else{
				$result['data_category'][$value->categoria]->existencia += $value->existencia;
			}


		}

		return $result;

		
	}

	

}
