<?php

namespace App\Controllers;

/**
 * Class Home
 *
 * @property NUBE narcisse
 * @package  Nube narcisse
 * @author   Erick Aguirre chununo@gmail.com
 */
class Home extends BaseController
{

    /**
     * IonAuth library
     *
     * @var \IonAuth\Libraries\IonAuth
     */
    protected $ionAuth;


    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->ionAuth    = new \App\Libraries\IonAuth();
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


        if (!$this->ionAuth->loggedIn()) {
        
            return redirect()->to('/auth/login')->send();
        
        }else{

            //if ($this->ionAuth->isAccessPage()) {

                if (method_exists($this, $method))  {

                    return $this->$method(...$params);
                }
            //}

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
    }

    public function index()
    {   

        return view('welcome_message');
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
