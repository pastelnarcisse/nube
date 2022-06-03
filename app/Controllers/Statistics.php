<?php

namespace App\Controllers;


use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
/**
 * Class Home
 *
 * @property NUBE narcisse
 * @package  Nube narcisse
 * @author   Erick Aguirre chununo@gmail.com
 */
class Statistics extends BaseController
{
    use ResponseTrait;

    protected $request;
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
    protected $statistics;

    protected $ionAuthModel;

    protected $header;

    protected $body;

    protected $menu;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->ionAuth    = new \App\Libraries\IonAuth();
        $this->statistics    = new \App\Libraries\Statistics();
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULO DE VISTAS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function index() {   
        
        echo view('headermain',$this->header);
        echo view('bodymain', $this->body);
        echo view('footermain');
    }

    public function dashboard($date = ""){

        if ($date == "") {
            $date = date('Y-m-d');
        }

        if ($this->request->getJSON()) {
            $post = $this->request->getJSON();
            $date = isset($post->date) ? $post->date : date('Y-m-d');
        }

        if (!array_search('buscar', $this->body['permissions']['actions'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Falta permiso buscar');
        }

        if ($idAction = array_search('changeDate', $this->body['permissions']['actions'])) {
            $this->body['dateDashboardChange']['data'] = true;
            $this->body['dateDashboardChange']['info'] = $this->body['permissions']['permissions'][$idAction];
        }else{
            $date = date('Y-m-d');
        }

        $this->body['dateDashboard'] = $date;

        if ($idAction = array_search('countSales', $this->body['permissions']['actions'])) {
            $this->body['countSales'] = $this->statistics->countSales($date,$this->ionAuth->getStoresID());
            $this->body['countSales']['info'] = $this->body['permissions']['permissions'][$idAction];
        }

        if ($idAction = array_search('sumSales', $this->body['permissions']['actions'])) {
            $this->body['sumSales'] = $this->statistics->sumSales($date,$this->ionAuth->getStoresID());
            $this->body['sumSales']['info'] = $this->body['permissions']['permissions'][$idAction];
        }

        if ($idAction = array_search('tblSaleStore', $this->body['permissions']['actions'])) {
            $this->body['tblSaleStore'] = $this->statistics->salesStoreByDate($date,$this->ionAuth->getStoresID());
            $this->body['sumCredit']   = 0;
            foreach ($this->body['tblSaleStore']['data'] as $value) {
                $this->body['sumCredit'] =+ $value->credito;
            }
            $this->body['tblSaleStore']['info'] = $this->body['permissions']['permissions'][$idAction];
        }

        if ($idAction = array_search('tblStockCategory', $this->body['permissions']['actions'])) {
            $this->body['tblStockCategory'] = $this->statistics->stockCategory($date,$this->ionAuth->getStoresID());
            $this->body['tblStockCategory']['info'] = $this->body['permissions']['permissions'][$idAction];
        }

        if ($this->request->getJSON()) {
            return $this->respond($this->body, 200);
        }

        echo view('headermain',$this->header);
        echo view('statistics/dashboard', $this->body);
        echo view('footermain');
    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULO POST
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * OBTENER VENTAS GENERALES
     * Se obtiene las ventas generales por los datos obtenidos por post de tipo json
     * @param respuesta por post JSON
     * 
     * @return array respuesta con datos de error y mensaje
     * 
     * */
    public function getActualSales(){

        $post = $this->request->getJSON();
        
        $date = isset($post->date) ? $post->date : date('Y-m-d');

        $response['post'] = $post;
        $response['tblSaleStore'] =  $this->statistics->salesStoreByDate($date,$this->ionAuth->getStoresID());
  
        return $this->respond($response, 200);
       

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
