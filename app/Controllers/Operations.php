<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Mpdf\Tag\I;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class Home
 *
 * @property NUBE narcisse
 * @package  Nube narcisse
 * @author   Erick Aguirre chununo@gmail.com
 */
class Operations extends BaseController
{   
	use ResponseTrait;
   

	/**
	 * IonAuth library
	 *
	 * @var \IonAuth\Libraries\IonAuth
	 */
	protected $ionAuth;

	/**
	 * Statistics library
	 *
	 * @var \IonAuth\Libraries\Statistics
	 */
	protected $reports;

	protected $ionAuthModel;

	protected $header;

	protected $body;

	protected $menu;

	protected $request;

	protected $reportsModel;

	protected $stock;

	protected $inventary;

	protected $call;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->ionAuth    		= new \App\Libraries\IonAuth();
		$this->reports    		= new \App\Libraries\Reports();
		$this->reportsModel    	= new \App\Models\ReportsModel();
		$this->ionAuthModel 	= new \App\Models\IonAuthModel();
		helper(['form', 'url']);
		$this->session       	= \Config\Services::session();
		$this->inventary 		= new \App\Libraries\Inventary();
		
		
	}

	/**
	 * Remap
	 *
	 * @return redirecciona
	 */
	public function _remap($method, ...$params) {

		if ($method == 'login') {
			return $this->$method(...$params);
		}

		// if ($this->request->getJSON()) {
			
		//     if (method_exists($this, $method))  {

		//         return $this->$method(...$params);
		//     }
			
		// }


		if (!$this->ionAuth->loggedIn()) {
			return redirect()->to('/auth/login')->send();
		}else{

			if ($this->ionAuth->isAccesMenu()) {

				if (method_exists($this, $method))  {

					$this->header['menus'] = $this->ionAuth->getMenus();
					$this->header['module'] = $this->ionAuth->getDataModule();
					$this->body['modules'] = $this->ionAuth->getModules();
					$this->body['module'] = $this->ionAuth->getDataModule();
					$this->body['stores'] = $this->ionAuth->getStores();
					$this->body['permissions'] = $this->ionAuth->getActionModule();
					$this->body['user_id'] = $this->ionAuth->getUserId();

					return $this->$method(...$params);
				}
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No existe página');
			}

			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Sin permisos para '.$method);
		}
		
	}

	/**
	 * funcion principal
	 * Se visualizan los modulos activos
	 * 
	 * */
	public function dashboard() {   
		
		echo view('headermain',$this->header);
		echo view('bodymain', $this->body);
		echo view('footermain');
	}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULO DE VISTAS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Módulo de consulta de ventas
	 * 
	 * */
	public function inventoryAdjustment($date = "", $tab = "general"){

		if (!array_search('findAdjustment', $this->body['permissions']['actions'])) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Falta permiso buscar');
		}

		if ($date == "") {
			$date = date('Y-m-d');
		}

		$this->body['startDate'] = $date;
		$this->body['finishDate'] = $date;
		$this->body['tab'] = $tab;

		$this->body['departments'] = $this->reports->getDepartment();
		$this->body['categorys'] = $this->reports->getCategory();


		if ($this->request->getJSON()) {
			$details = isset($this->request->getJSON()->details) ? $this->request->getJSON()->details : [] ;
			$comment = isset($this->request->getJSON()->comment) ? $this->request->getJSON()->comment : [] ;
			$result = $this->inventary->setChangeStock($details,$comment);

			return $this->respond($result, 200);
		}   


		echo view('headermain',$this->header);
		echo view('operations/inventoryAdjustment', $this->body);
		echo view('footermain');
	}

	/**
	 * Módulo de consulta de ventas
	 * 
	 * */
	public function inventoryStatus($date = ""){

		

		if (!array_search('viewAdjustment', $this->body['permissions']['actions'])) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Falta permiso ver');
		}

		$this->body['listInventary'] = $this->inventary->getListInventary();

		if ($date == "") {
			$date = date('Y-m-d');
		}

		$this->body['startDate'] = $date;
		$this->body['finishDate'] = $date;

		$this->body['departments'] = $this->reports->getDepartment();
		$this->body['categorys'] = $this->reports->getCategory();

		if ($this->request->getJSON()) {
			//$this->body['listInventary'] = $this->inventary->getListInventary();

			$input = $this->request->getJSON();

			$result = [];

			// Dame los inventarios
			if($input->functionName == 'getListInventary'){
				$result = isset($input->adjustmentStartdate) ? $this->inventary->getListInventary($input->storesId,$input->adjustmentStartdate, $input->adjustmentFinishdate, $input->status) : [] ;
			}

			// Dame los datalles del inventario
			if ($input->functionName == 'getListInventaryDetail') {
				$result = isset($input->idAdjustment) ? $this->inventary->getListInventaryDetailById($input->idAdjustment) : [] ;
			}

			// Se envia status
			if ($input->functionName == 'adjustmentStatus') {
				
				try {
					
					$statusActual =  $this->inventary->getStatus($input->idAdjustment);

					$idAdjustment = $input->idAdjustment;

					// Si el status enviado y el actual son iguales, manda error
					if (intval($statusActual) == intval($input->status)) {
						$result['mensaje'] = 'Status ya guardado';
						$result['object'] = $statusActual;
						return $this->respond((object)$result, 401);
					}

					// Si status 1 entonces aceptar
					if ($input->status == 1 || $input->status == '1' ) {
						$result = $this->inventary->setInventaryAcepted($idAdjustment);
					}
					// Si status -1 entonces cancelar
					if ($input->status == -1 || $input->status == '-1') {
						$result = $this->inventary->setInventaryCanceled($idAdjustment);
					}
					// Si status 2, entonces aplicar
					if ($input->status == 2 || $input->status == '2') {

						// Datos de inventario
						$data = isset($input->idAdjustment) ? $this->inventary->getListInventaryDetailById($input->idAdjustment) : [] ;
						// llamada local
						$call =  new \App\Libraries\CallLocal('app/setAjusteInventario',$data);
						// Aplicar ajuste de inventario y dame respuesta
						$result = $call->response();

						if ($result->code == 200) {
							$ain_id = $result->object->ain_id;
							// Se guarda la actualizacion de inventario en nube
							$result->setInventaryAplicate = $this->inventary->setInventaryAplicate($idAdjustment, $ain_id);
						}
					}
				} catch (\Throwable $th) {
					return $this->respond((object)$th, 400);
				}

				
			}

			return $this->respond((object)$result, 200);
		}   
		
		

		echo view('headermain',$this->header);
		echo view('operations/inventoryStatus', $this->body);
		echo view('footermain');
	}


	


/*****************************************CONSOLE**************************************/    
	/**
	* Retorna envio en consola de javascript
	* 
	* @access public
	* @param $data all Es cualquier objeto
	* @return array Arreglo con código javascript.
	* @author Chununo <chununo@hotmail.com> 
	*/
	public function console($data){
		$output = json_encode($data);
		echo "<script>console.log( ". $output ." );</script>";
	}
}
