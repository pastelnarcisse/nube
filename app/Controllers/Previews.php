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
class Previews extends BaseController
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
                    $this->body['modules'] = $this->ionAuth->getModules();
                    $this->body['module'] = $this->ionAuth->getDataModule();
                    $this->body['stores'] = $this->ionAuth->getStores();
                    $this->body['permissions'] = $this->ionAuth->getActionModule();

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
    public function saleDates($date = "", $tab = "general"){

        if (!array_search('buscar', $this->body['permissions']['actions'])) {
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


        echo view('headermain',$this->header);
        echo view('previews/saleDates', $this->body);
        echo view('footermain');
    }

    /**
     * Módulo de consulta de ventas
     * 
     * */
    public function stockStore($start_date = "", $finish_date = ""){

        if (!array_search('buscar', $this->body['permissions']['actions'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Falta permiso buscar');
        }

        if ($start_date == "") {
            $start_date = date('Y-m-d');
        }

        if ($finish_date == "") {
            $finish_date = date('Y-m-d');
        }

        $this->stock                = new \App\Libraries\Stock();
        $this->body['departments']  = $this->reports->getDepartment();
        $this->body['categorys']    = $this->reports->getCategory();
        
        $cat_ids        = $this->body['categorys']['cat_ids'];
        $suc_ids        = $this->ionAuth->getStoresID();

        if ($this->request->getJSON()) {
            
            $post           = $this->request->getJSON();

            $start_date     = isset($post->start_date) ? $post->start_date : date('Y-m-d');
            $finish_date    = isset($post->finish_date) ? $post->finish_date : date('Y-m-d');
            $suc_ids        = $post->suc_ids;
            $cat_ids        = $post->cat_ids;

            $this->body['post']     = $post;

        }else{
            
        }

        $this->stock->setCategoryIDs($cat_ids);
        $this->stock->setDates($start_date, $finish_date);
        $this->body['stockStore'] = $this->stock->getStockStore();
        $this->body['suc_ids']  = $suc_ids;
        $this->body['cat_ids']  = $cat_ids;
        

        $this->body['startDate'] = $start_date;
        $this->body['finishDate'] = $finish_date;

        

        if ($this->request->getJSON()) {
            return $this->respond($this->body, 200);
        }    


        echo view('headermain',$this->header);
        echo view('previews/stockStore', $this->body);
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
