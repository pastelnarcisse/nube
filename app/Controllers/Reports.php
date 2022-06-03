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
class Reports extends BaseController
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

        if ($this->request->getJSON()) {
            
            if (method_exists($this, $method))  {

                return $this->$method(...$params);
            }
            
        }


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
    public function index() {   
        
        echo view('headermain',$this->header);
        echo view('bodymain', $this->body);
        echo view('footermain');
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULO DE VISTAS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Módulo de reporte de ventas, se visualizara el formulario para descargar, visualizar o enviar las ventas.
     * 
     * */
    public function sale($date = "", $tab = "general"){

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
        echo view('reports/sale', $this->body);
        echo view('footermain');
    }

    /**
     * Módulo de reporte de articulos, se visualizara el formulario para descargar, visualizar o enviar los articulo.
     * 
     * */
    public function products($date = "", $tab = "stock"){

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
        echo view('reports/products', $this->body);
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
    public function getSalesGeneral(){

        $post = $this->request->getJSON();



        $errors = array();

        // Checar arreglos
        if (empty($post->movement)) {
            $errors['movement'] = 'falta seleccionar tipo de movimiento';   
        }
        if (empty($post->suc_ids)) {
            $errors['suc_ids'] = 'falta seleccionar sucursal';   
        }
        if (empty($post->payment)) {
            $errors['payment'] = 'falta seleccionar tipo de pago';   
        }
        if (empty($post->status)) {
            $errors['status'] = 'falta seleccionar estado';   
        }

        if ($post->doc == 'preview') {

            $response = $this->reports->createPDFSaleGeneral($post);

            $pdf = $response['file'];
            $pdf = str_replace('data:application/pdf;base64,', '', $pdf);
            $pdf = str_replace(' ', '+', $pdf);
            $data = base64_decode($pdf);
            $f=fopen('public/report/pdf/tipoventa.pdf','w');
            fwrite($f,$data);
            fclose($f);
            
        }

        if ($post->doc == 'excel') { $response = $this->reports->createExcelSaleGeneral($post); }

        if ($post->doc == 'pdf') { $response = $this->reports->createPDFSaleGeneral($post); }

        if ($post->doc == 'email') {

            $response = $this->reports->createExcelSaleGeneral($post);

            $exe = $response['file'];
            $exe = str_replace('data:application/vnd.ms-Excel;base64,', '', $exe);
            $exe = str_replace(' ', '+', $exe);
            $data = base64_decode($exe);
            $f=fopen('public/report/excel/tipoventa.xlsx','w');
            fwrite($f,$data);
            fclose($f);

            $email = \Config\Services::email();
            $email->setFrom('administrator@narcisse.mx', 'Reporte de tipo de ventas');
            $email->setTo($_SESSION['email']);
            $email->setSubject('Email envio');
            $email->setMessage('Se ENVIO REPORTE');//your message here

            // $email->setCC('another@emailHere');//CC
            // $email->setBCC('thirdEmail@emialHere');// and BCC
            $filename = 'public/report/excel/tipoventa.xlsx'; //you can use the App patch 
            $email->attach($filename);

            $email->send();
            // $email->printDebugger(['headers']);
            return $response;
        }

        $response['post'] = $post;

        if (!empty($errors)) { return $this->fail($errors, 400);

        }else{

            if ($response['codigo'] == 500) { return $this->fail($response, 500); }

            return $this->respond($response, 200);
        }

    }

    /**
     * OBTENER VENTAS POR ARTICULOS
     * Se obtiene las ventas de articulos por los datos obtenidos por post de tipo json
     * @param respuesta por post JSON
     * @return array respuesta con datos de error y mensaje
     * 
     * */
    public function getSalesProducts(){

        $post = $this->request->getJSON();

        $errors = array();

        $response = [];

        $response['codigo'] = 200;

        $response['post'] = $post;

        // Checar arreglos
        if (empty($post->categorys)) {
            $errors['movement'] = 'falta seleccionar tipo de movimiento';   
        }
        if (empty($post->movement)) {
            $errors['movement'] = 'falta seleccionar tipo de movimiento';   
        }
        if (empty($post->suc_ids)) {
            $errors['suc_ids'] = 'falta seleccionar sucursal';   
        }

        if (empty($post->status)) {
            $errors['status'] = 'falta seleccionar estado';   
        }

        //$getData = $this->reports->getSalesProducts($post);
        // $response['query'] = $getData;

        if ($post->doc == 'preview') {

            $response = $this->reports->createPDFSaleProduct($post);

            $pdf = $response['file'];
            $pdf = str_replace('data:application/pdf;base64,', '', $pdf);
            $pdf = str_replace(' ', '+', $pdf);
            $data = base64_decode($pdf);
            $f=fopen('public/report/pdf/articuloventa.pdf','w');
            fwrite($f,$data);
            fclose($f);
            
        }

        if ($post->doc == 'excel') { $response = $this->reports->createExcelSaleProduct($post); }

        if ($post->doc == 'pdf') { $response = $this->reports->createPDFSaleProduct($post); }

        if ($post->doc == 'email') {

            $response = $this->reports->createExcelSaleProduct($post);

            $exe = $response['file'];
            $exe = str_replace('data:application/vnd.ms-Excel;base64,', '', $exe);
            $exe = str_replace(' ', '+', $exe);
            $data = base64_decode($exe);
            $f=fopen('public/report/excel/articuloventa.xlsx','w');
            fwrite($f,$data);
            fclose($f);

            $email = \Config\Services::email();
            $email->setFrom('administrator@narcisse.mx', 'Reporte de tipo de ventas');
            $email->setTo($_SESSION['email']);
            $email->setSubject('Email envio');
            $email->setMessage('Se ENVIO REPORTE');//your message here

            // $email->setCC('another@emailHere');//CC
            // $email->setBCC('thirdEmail@emialHere');// and BCC
            $filename = 'public/report/excel/articuloventa.xlsx'; //you can use the App patch 
            $email->attach($filename);

            $email->send();
            // $email->printDebugger(['headers']);
            return $response;
        }

        if (!empty($errors)) { return $this->fail($errors, 400);

        }else{

            if ($response['codigo'] == 500) { return $this->fail($response, 500); }

            return $this->respond($response, 200);
        }
        
    }

    /**
     * OBTENER EXISTENCIAS DE ARTÍCULOS
     * Se obtiene las existencias de articulos por los datos obtenidos por post de tipo json
     * @param respuesta por post JSON
     * 
     * @return array respuesta con datos de error y mensaje
     * 
     * */
    public function getProductsStock(){

        $post = $this->request->getJSON();

        $errors = array();

        // Checar arreglos
        if (empty($post->categorys)) {
            $errors['categorys'] = 'falta seleccionar categorías';   
        }
        if (empty($post->suc_ids)) {
            $errors['suc_ids'] = 'falta seleccionar sucursal';   
        }

        $response['post'] = $post;


        if (!empty($errors)) { 
            return $this->fail($errors, 400);

        }else{





            if ($post->doc == 'preview') {

                $response = $this->reports->createPDFProductsStock($post);

                $pdf = $response['file'];
                $pdf = str_replace('data:application/pdf;base64,', '', $pdf);
                $pdf = str_replace(' ', '+', $pdf);
                $data = base64_decode($pdf);
                $f=fopen('public/report/pdf/inventarioArticulo.pdf','w');
                fwrite($f,$data);
                fclose($f);
                
            }

            if ($post->doc == 'excel') { $response = $this->reports->createExcelProductsStock($post); }

            if ($post->doc == 'pdf') { $response = $this->reports->createPDFProductsStock($post); }

            if ($post->doc == 'email') {

                $response = $this->reports->createExcelProductsStock($post);

                $exe = $response['file'];
                $exe = str_replace('data:application/vnd.ms-Excel;base64,', '', $exe);
                $exe = str_replace(' ', '+', $exe);
                $data = base64_decode($exe);
                $f=fopen('public/report/excel/inventarioArticulo.xlsx','w');
                fwrite($f,$data);
                fclose($f);

                $email = \Config\Services::email();
                $email->setFrom('administrator@narcisse.mx', 'Reporte de inventarios');
                $email->setTo($_SESSION['email']);
                $email->setSubject('Email envio');
                $email->setMessage('Se ENVIO REPORTE');//your message here

                // $email->setCC('another@emailHere');//CC
                // $email->setBCC('thirdEmail@emialHere');// and BCC
                $filename = 'public/report/excel/inventarioArticulo.xlsx'; //you can use the App patch 
                $email->attach($filename);

                $email->send();
                // $email->printDebugger(['headers']);
                return $response;
            }

            if ($response['codigo'] == 500) { return $this->fail($response, 500); }

            return $this->respond($response, 200);
        }

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
