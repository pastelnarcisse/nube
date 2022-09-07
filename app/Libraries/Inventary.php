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
class Inventary
{
	/**
	 * Configuration
	 *
	 * @var \IonAuth\Config\IonAuth
	 */
	protected $config;


	protected $nubeModel;

	protected $ionAuth;

	protected $errorMessage;


	/**
	 * __construct
	 *
	 * @author Chununo
	 */
	public function __construct(){
		
		$this->config = config('NubeNarcisse');

		helper('cookie');

		$this->session = session();

		$this->nubeModel = new \App\Models\NubeModel();

		$this->ionAuth    = new \App\Libraries\IonAuth();

	}


	public function setChangeStock($details, $comment = 'Sin comentario'){

		if (empty($details)) {
			$this->errorMessage = 'Esta vacio los detalles de artículos';
			return false;
		}

		$adjustment_date = date('Y-m-d H:i:s');
		$adjustment_user = $this->ionAuth->getUserId();
		$status = 0;
		$store_id = isset($details[0]->sucursal) ? $details[0]->sucursal : 0;
		$inventary_adjustment_detail = array();

		if ($store_id == 0) {
			$this->errorMessage = 'No hay sucursal';
			return false;
		}

		$inventary_adjustment = array(
			'adjustment_user' 	=> $adjustment_user,
			'adjustment_date'	=> $adjustment_date,
			'store_id' 			=> $store_id,
			'comentario'		=> $comment
		);

		$invAdj = $this->nubeModel->saveInventaryAdjustment($inventary_adjustment);

		if ($invAdj['status'] != 200) {
			return $invAdj;
		}

		$id_inventory_adjustment = $invAdj['object'];

		foreach ($details as $detail) {

			$iad = array(
				'id_inventory_adjustment'=> $id_inventory_adjustment,
				'art_id' 				=> $detail->art_id,
				'clave'					=> $detail->clave,
				'descripcion'			=> $detail->articulo,
				'ajuste'				=> $detail->ajustar,
				'existenciaAnterior'	=> $detail->existencia_actual,
				'existenciaActual'		=> $detail->nueva_existencia
			);

			

			$invAdjDet = $this->nubeModel->saveInventaryAdjustmentDetail($iad);

			if ($invAdjDet['status'] != 200) {
				return $invAdjDet;
			}

		}

		return $invAdj;
	}


	public function getListInventary($storesId = [],$startDate = false, $finishDate = false, $status = []){
		
		//$storesId = $this->ionAuth->getStoresID();

		$list = $this->nubeModel->getInventaryListByStore($storesId, $startDate, $finishDate, $status);
		$list['stores'] = $storesId;

		return $list;

	}

	public function getListInventaryDetailById($idAdjustment){
		
		//$storesId = $this->ionAuth->getStoresID();

		$list = $this->nubeModel->getListInventaryDetailById($idAdjustment);

		return $list;

	}

		/**
	 * Se acepta el ajuste de inventario
	 */
	public function getStatus($id = false){
		if (!$id) {
			return false;
		}

		$res = $this->nubeModel->getStatus($id);

		return $res['object']->status;
	}


	/**
	 * Se acepta el ajuste de inventario
	 */
	public function setInventaryAcepted($id = false){
		if (!$id) {
			return false;
		}

		return $this->nubeModel->setStatusInventary($id, 1);
	}

	/**
	 * Se cancela el ajuste de inventario
	 */
	public function setInventaryCanceled($id = false){
		if (!$id) {
			return false;
		}

		return $this->nubeModel->setStatusInventary($id, -1);
	}

	/**
	 * Se cancela el ajuste de inventario
	 */
	public function setInventaryAplicate($id = false, $ain_id){
		if (!$id) {
			return false;
		}

		$applied_user = $this->ionAuth->getUserId();
		$applied_date = date('Y-m-d H:m:s');

		return $this->nubeModel->setApplicated($id, $applied_date, $applied_user, $ain_id);
	}




	public function getErrorMessagge(){
		return $this->errorMessage;
	}
	
	

}
