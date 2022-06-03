<?php
namespace App\Libraries;

/**
 * Name:    Stock
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
class Stock
{
	/**
	 * Configuration
	 *
	 * @var \IonAuth\Config\IonAuth
	 */
	protected $config;

	protected $ionAuth;

	/**
	 * StructureModel model
	 *
	 * @var \IonAuth\Models\StructureModel
	 */
	protected $structureModel;

	protected $historialModel;

	protected $category_ids = [];

	protected $stores_ids = [];

	protected $stockStore = [];

	protected $startDate;

	protected $finishDate;

	/**
	 * __construct
	 *
	 * @author Chununo <chununo@gmail.com>
	 */
	public function __construct($stores_ids = [], $category_ids = [], $startDate = "", $finishDate = "")
	{
		
		helper('cookie');		

		$this->config 			= config('NubeNarcisse');

		$this->session 			= session();

		$this->ionAuth 			= new \App\Libraries\IonAuth();

		$this->structureModel 	= new \App\Models\StructureModel();

		$this->historialModel	= new \App\Models\HistorialModel();

		if (empty($stores_ids)) {
			$stores_ids = $this->ionAuth->getStoresID();
		}

		$this->stores_ids 		= $stores_ids;


		if (empty($category_ids)) {
			$category_ids = $this->structureModel->getCategoryIDs([1]);
		}

		if (empty($startDate)) {
			$this->startDate = date('Y-m-d');
		}

		if (empty($finishDate)) {
			$this->finishDate = date('Y-m-d');
		}

		$this->category_ids 	= $category_ids;


	}

	public function setCategoryIDs($category_ids){

		$this->category_ids = $category_ids;

	}

	public function setDates($startDate, $finishDate = ""){
		if (empty($finishDate)) {
			$this->finishDate = $startDate;
		}
		$this->startDate = $startDate;
	}


	public function getStockStore(){

		$result = array('messsage' =>  'entro');

		if (empty($this->stores_ids)) {
			$result['error'] = 'no hay tiendas';
			$result['status'] = 400;
			return  $result;
		}

		if (empty($this->category_ids)) {
			$result['error'] = 'no hay categorias';
			$result['status'] = 400;
			return $result;
		}

		$this->stockStore = $this->historialModel->getStoreByStoreCategoyIDs($this->stores_ids, $this->category_ids, $this->startDate, $this->finishDate);

		return $this->stockStore;

	}



	
	

}
