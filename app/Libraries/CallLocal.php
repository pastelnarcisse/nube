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
class CallLocal{
	/**
	 * Configuration
	 *
	 * @var \IonAuth\Config\IonAuth
	 */
	protected $config;

	public $ws = "test";

	public $json = "{}";
	/**
	 * __construct
	 *
	 * @author Chununo
	 */
	public function __construct($ws, $json = false){
		
		$this->config = config('NubeNarcisse');

		helper('cookie');

		$this->session = session();

		$this->ionAuth    = new \App\Libraries\IonAuth();

		$this->ws = $ws;

		$this->json = !$json ? $this->json : json_encode($json);

	}


	public function response(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'localhost/replicador/ws/'.$this->ws,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $this->json,
			CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
			),
		));

		$response = json_decode(curl_exec($curl));

		curl_close($curl);


		return $response;
	}




}
