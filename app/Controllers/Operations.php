<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
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

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->ionAuth    = new \App\Libraries\IonAuth();
        $this->reports    = new \App\Libraries\Reports();
        $this->reportsModel    = new \App\Models\ReportsModel();
        $this->ionAuthModel = new \App\Models\IonAuthModel();
        helper(['form', 'url']);
        $this->session       = \Config\Services::session();
        $this->inventary = new \App\Libraries\Inventary();
        


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

            $result = $this->inventary->setChangeStock($details);

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
    public function inventoryStatus($date = "", $tab = "general"){

        if (!array_search('viewAdjustment', $this->body['permissions']['actions'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Falta permiso ver');
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

            $result = $this->inventary->setChangeStock($details);

            return $this->respond($result, 200);
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
