<?php
namespace App\Libraries;

/**
 * Name:    Ion Auth
 *
 * Created:  10.01.2009
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
class IonAuth
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
	 * @var \IonAuth\Models\IonAuthModel
	 */
	protected $ionAuthModel;

	/**
	 * Email class
	 *
	 * @var \CodeIgniter\Email\Email
	 */
	protected $email;

	/**
	 * __construct
	 *
	 * @author Ben
	 */
	public function __construct()
	{
		// Check compat first
		$this->checkCompatibility();

		$this->config = config('IonAuth');

		$this->email = \Config\Services::email();
		helper('cookie');

		$this->session = session();

		$this->ionAuthModel = new \App\Models\IonAuthModel();

		$emailConfig = $this->config->emailConfig;

		if ($this->config->useCiEmail && isset($emailConfig) && is_array($emailConfig))
		{
			$this->email->initialize($emailConfig);
		}

		$this->ionAuthModel->triggerEvents('library_constructor');
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 * @param string $method    Method to call
	 * @param array  $arguments Method arguments
	 *
	 * @return mixed
	 * @throws Exception When $method is undefined.
	 */
	public function __call(string $method, array $arguments)
	{
		if (! method_exists( $this->ionAuthModel, $method))
		{
			throw new \Exception('Undefined method Ion_auth::' . $method . '() called');
		}
		if ($method === 'create_user')
		{
			return call_user_func_array([$this, 'register'], $arguments);
		}
		if ($method === 'update_user')
		{
			return call_user_func_array([$this, 'update'], $arguments);
		}
		return call_user_func_array([$this->ionAuthModel, $method], $arguments);
	}

	/**
	 * Forgotten password feature
	 *
	 * @param string $identity Identity
	 *
	 * @return array|boolean
	 * @author Mathew
	 */
	public function forgottenPassword(string $identity)
	{
		// Retrieve user information
		$user = $this->where($this->ionAuthModel->identityColumn, $identity)
					 ->where('active', 1)
					 ->users()->row();

		if ($user)
		{
			// Generate code
			$code = $this->ionAuthModel->forgottenPassword($identity);

			if ($code)
			{
				$data = [
					'identity'              => $identity,
					'forgottenPasswordCode' => $code,
				];

				if (! $this->config->useCiEmail)
				{
					$this->setMessage('IonAuth.forgot_password_successful');
					return $data;
				}
				else
				{
					$message = view($this->config->emailTemplates . $this->config->emailForgotPassword, $data);
					$this->email->clear();
					$this->email->setFrom($this->config->adminEmail, $this->config->siteTitle);
					$this->email->setTo($user->email);
					$this->email->setSubject($this->config->siteTitle . ' - ' . lang('IonAuth.email_forgotten_password_subject'));
					$this->email->setMessage($message);
					if ($this->email->send())
					{
						$this->setMessage('IonAuth.forgot_password_successful');
						return true;
					}
				}
			}
		}

		$this->setError('IonAuth.forgot_password_unsuccessful');
		return false;
	}

	/**
	 * Forgotten password check
	 *
	 * @param string $code Code
	 *
	 * @return object|boolean
	 * @author Michael
	 */
	public function forgottenPasswordCheck(string $code)
	{
		$user = $this->ionAuthModel->getUserByForgottenPasswordCode($code);

		if (! is_object($user))
		{
			$this->setError('IonAuth.password_change_unsuccessful');
			return false;
		}
		else
		{
			if ($this->config->forgotPasswordExpiration > 0)
			{
				//Make sure it isn't expired
				$expiration = $this->config->forgotPasswordExpiration;
				if (time() - $user->forgotten_password_time > $expiration)
				{
					//it has expired
					$identity = $user->{$this->config->identity};
					$this->ionAuthModel->clearForgottenPasswordCode($identity);
					$this->setError('IonAuth.password_change_unsuccessful');
					return false;
				}
			}
			return $user;
		}
	}

	/**
	 * Register
	 *
	 * @param string $identity       Identity
	 * @param string $password       Password
	 * @param string $email          Email
	 * @param array  $additionalData Additional data
	 * @param array  $groupIds       Groups id
	 *
	 * @return integer|array|boolean The new user's ID if e-mail activation is disabled or Ion-Auth e-mail activation
	 *                               was completed;
	 *                               or an array of activation details if CI e-mail validation is enabled; or false
	 *                               if the operation failed.
	 * @author Mathew
	 */
	public function register(string $identity, string $password, string $email, array $additionalData = [], array $groupIds = [])
	{
		$this->ionAuthModel->triggerEvents('pre_account_creation');

		$emailActivation = $this->config->emailActivation;

		$id = $this->ionAuthModel->register($identity, $password, $email, $additionalData, $groupIds);

		if (! $emailActivation)
		{
			if ($id !== false)
			{
				$this->setMessage('IonAuth.account_creation_successful');
				$this->ionAuthModel->triggerEvents(['post_account_creation', 'post_account_creation_successful']);
				return $id;
			}
			else
			{
				$this->setError('IonAuth.account_creation_unsuccessful');
				$this->ionAuthModel->triggerEvents(['post_account_creation', 'post_account_creation_unsuccessful']);
				return false;
			}
		}
		else
		{
			if (! $id)
			{
				$this->setError('IonAuth.account_creation_unsuccessful');
				return false;
			}

			// deactivate so the user must follow the activation flow
			$deactivate = $this->ionAuthModel->deactivate($id);

			// the deactivate method call adds a message, here we need to clear that
			$this->ionAuthModel->clearMessages();

			if (! $deactivate)
			{
				$this->setError('IonAuth.deactivate_unsuccessful');
				$this->ionAuthModel->triggerEvents(['post_account_creation', 'post_account_creation_unsuccessful']);
				return false;
			}

			$activationCode = $this->ionAuthModel->activationCode;
			$identity       = $this->config->identity;
			$user           = $this->ionAuthModel->user($id)->row();

			$data = [
				'identity'   => $user->{$identity},
				'id'         => $user->id,
				'email'      => $email,
				'activation' => $activationCode,
			];
			if (! $this->config->useCiEmail)
			{
				$this->ionAuthModel->triggerEvents(['post_account_creation', 'post_account_creation_successful', 'activation_email_successful']);
				$this->setMessage('IonAuth.activation_email_successful');
				return $data;
			}
			else
			{
				$emailSent = $this->sendEmail(
					$email,
					$this->config->siteTitle . ' - ' . lang('IonAuth.emailActivation_subject'),
					$this->config->emailTemplates . $this->config->emailActivate,
					$data
				);

				if ($emailSent) {
					$this->ionAuthModel->triggerEvents(['post_account_creation', 'post_account_creation_successful', 'activation_email_successful']);
					$this->setMessage('IonAuth.activation_email_successful');
					return $id;
				}
			}

			$this->ionAuthModel->triggerEvents(['post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful']);
			$this->setError('IonAuth.activation_email_unsuccessful');
			return false;
		}
	}

	/**
	 * Send activation email.
	 *
	 * @param string $identity
	 *
	 * @return boolean|array return an array of activation details if CI e-mail validation is enabled
	 * @author Ali Ragab
	 */
	public function sendActivationEmail(string $identity)
	{
		if (empty($identity)) {
			$this->setError('IonAuth.empty_identity');
			return FALSE;
		}

		if (!$this->ionAuthModel->identityCheck($identity)) {
			$this->setError("IonAuth.unregistered_identity");
			return FALSE;
		}

		// Retrieve user information
		$user = $this->where($this->ionAuthModel->identityColumn, $identity)
			->limit(1)
			->users()->row();

		if ($user->active) {
			$this->setError("IonAuth.already_activated_identity");
			return FALSE;
		}

		// deactivate so the user must follow the activation flow
		$deactivate = $this->ionAuthModel->deactivate($user->id);

		// the deactivate method call adds a message, here we need to clear that
		$this->ionAuthModel->clearMessages();

		if (!$deactivate) {
			$this->setError('IonAuth.deactivate_unsuccessful');
			return FALSE;
		}

		$activationCode = $this->ionAuthModel->activationCode;
		$identity       = $this->config->identity;

		$data = [
			'identity'   		  => $user->{$identity},
			'id'         		  => $user->id,
			'email'      		  => $user->email,
			'activation' 		  => $activationCode,
		];

		if (!$this->config->useCiEmail) {
			$this->ionAuthModel->triggerEvents(['activation_email_successful']);
			$this->setMessage('IonAuth.activation_email_successful');
			return $data;

		} else {

			$emailSent = $this->sendEmail(
				$user->email,
				$this->config->siteTitle . ' - ' . lang('IonAuth.emailActivation_subject'),
				$this->config->emailTemplates . $this->config->emailActivate,
				$data
			);

			if ($emailSent) {
				$this->triggerEvents(['activation_email_successful']);
				$this->setMessage('IonAuth.activation_email_successful');
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Send email to the user.
	 *
	 * @param string $user_email
	 * @param string $subject
	 * @param string $template
	 * @param array $data
	 *
	 * @return boolean
	 */
	public function sendEmail(
		string $user_email, 
		string $subject, 
		string $template, 
		array $data): bool
	{
		$message = view($template, $data);

		$this->email->clear();
		$this->email->setFrom($this->config->adminEmail, $this->config->siteTitle);
		$this->email->setTo($user_email);
		$this->email->setSubject($subject);
		$this->email->setMessage($message);

		if ($this->email->send() === true) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Logout
	 *
	 * @return true
	 * @author Mathew
	 */
	public function logout(): bool
	{
		$this->ionAuthModel->triggerEvents('logout');

		$identity = $this->session->get('user_id');
		
		$this->session->remove([$identity, 'id', 'user_id']);

		// delete the remember me cookies if they exist
		delete_cookie($this->config->rememberCookieName);

		// Clear all codes
		if (isset($identity)) {
			$this->ionAuthModel->clearForgottenPasswordCode($identity);
			$this->ionAuthModel->clearRememberCode($identity);
		}
		
		// Destroy the session
		$this->session->destroy();

		// Recreate the session
		session_start();

		session_regenerate_id(true);

		$this->setMessage('IonAuth.logout_successful');
		return true;
	}

	/**
	 * Auto logs-in the user if they are remembered
	 *
	 * @author Mathew
	 *
	 * @return boolean Whether the user is logged in
	 */
	public function loggedIn(): bool
	{
		$this->ionAuthModel->triggerEvents('logged_in');

		$recheck = $this->ionAuthModel->recheckSession();

		// auto-login the user if they are remembered
		if (! $recheck && get_cookie($this->config->rememberCookieName))
		{
			$recheck = $this->ionAuthModel->loginRememberedUser();
		}

		return $recheck;
	}

	/**
	 * Get user id
	 *
	 * @return integer|null The user's ID from the session user data or NULL if not found
	 * @author jrmadsen67
	 **/
	public function getUserId()
	{
		$userId = $this->session->get('user_id');
		if (! empty($userId))
		{
			return $userId;
		}
		return null;
	}

	/**
	 * Check to see if the currently logged in user is an admin.
	 *
	 * @param integer $id User id
	 *
	 * @return boolean Whether the user is an administrator
	 * @author Ben Edmunds
	 */
	public function isAdmin(int $id=0): bool
	{
		$this->ionAuthModel->triggerEvents('is_admin');

		$adminGroup = $this->config->adminGroup;

		return $this->loggedIn() && $this->ionAuthModel->inGroup($adminGroup, $id);
	}

	/**
	 * Check the compatibility with the server
	 *
	 * Script will die in case of error
	 *
	 * @return void
	 */
	protected function checkCompatibility()
	{
		// I think we can remove this method

		/*
		// PHP password_* function sanity check
		if (!function_exists('password_hash') || !function_exists('password_verify'))
		{
			show_error("PHP function password_hash or password_verify not found. " .
				"Are you using CI 2 and PHP < 5.5? " .
				"Please upgrade to CI 3, or PHP >= 5.5 " .
				"or use password_compat (https://github.com/ircmaxell/password_compat).");
		}
		*/

		/*
		// Compatibility check for CSPRNG
		// See functions used in Ion_auth_model::randomToken()
		if (!function_exists('random_bytes') && !function_exists('mcrypt_create_iv') && !function_exists('openssl_random_pseudo_bytes'))
		{
			show_error("No CSPRNG functions to generate random enough token. " .
				"Please update to PHP 7 or use random_compat (https://github.com/paragonie/random_compat).");
		}
		*/
	}

/**
 * Name:    Extension Ion Auth
 *
 * Created:  2021-11-04
 *
 * Description:  Modificado para fines de acceso a paginas y tiendas.
 *               
 * Los datos superiores perteneces al creador original y no se han echo ningo tipo de modificación.
 *
 * Requirements: PHP7.2 or above
 *
 * @package    CodeIgniter-Ion-Auth
 * @author     Erick Aguirre Mtz <chununo@gmail.com>
 * @filesource
 */
	
	/**
	 * Checar si el usuario tiene acceso al menu
	 *
	 * @param integer $id User id
	 *
	 * @return boolean El usuario accede al menu
	 * @author Chununo
	 */
	public function isAccesMenu(int $id = 0) {
		
		$this->ionAuthModel->triggerEvents('isAccesMenu');
		// Servicio de rutas
		$router = service('router');
		// Nombre del controlador, estructura: \APP_NAME\CONTROLADOR_NAME\METHOD_NAME
		$ruta_controlador = $router->controllerName();

		$ruta_controlador = explode("\\", $ruta_controlador);

		$controlador = $ruta_controlador[sizeof($ruta_controlador)-1];

		$metodo = $router->methodName();

		$modules = $this->ionAuthModel->inModule();

		$isAcces = false;

		if (sizeof($modules) > 0) {
			
			foreach ($modules as $i => $module) {

				if ($module->menuLink != null && strnatcasecmp ( $module->menuLink , $controlador.'/'.$metodo ) == 0) {
					$isAcces = true;
					break;
				}elseif($module->moduleLink != null && strnatcasecmp ( $module->moduleLink , $controlador.'/'.$metodo ) == 0) {
					$isAcces = true;
					break;
				}
			}
		}
		return $isAcces;
	}

	/**
	 * Checar si el usuario tiene acceso al módulo
	 *
	 * @param integer $id User id
	 *
	 * @return boolean El usuario accede al módulo
	 * @author Chununo
	 */
	public function isAccesModule(int $id = 0) {
		
		$this->ionAuthModel->triggerEvents('isAccesModule');
		// Servicio de rutas
		$router = service('router');
		// Nombre del controlador, estructura: \APP_NAME\CONTROLADOR_NAME\METHOD_NAME
		$ruta_controlador = $router->controllerName();

		$ruta_controlador = explode("\\", $ruta_controlador);

		$controlador = $ruta_controlador[sizeof($ruta_controlador)-1];

		$metodo = $router->methodName();

		$modules = $this->ionAuthModel->inModule();

		$isAcces = false;

		if (sizeof($modules) > 0) {
			
			foreach ($modules as $i => $module) {

				if ($module->moduleLink != null && strnatcasecmp ( $module->moduleLink , $controlador.'/'.$metodo ) == 0) {
					$isAcces = true;
				}
			}
		}
		return $modules;
	}

	/**
	 * Dame el menu
	 *
	 * @param integer $id User id
	 *
	 * @return boolean menu del usuario
	 * @author Chununo
	 */
	public function getMenus(int $id = 0)	{

		$this->ionAuthModel->triggerEvents('getMenus');

		$groups = $this->ionAuthModel->getUsersGroups()->getResult();

		$menu = [];

		foreach ($groups as $i => $group) {

			if (sizeof($this->ionAuthModel->permissionGroupMenu($group->id)->result()) > 0) {
				array_push($menu, ...$this->ionAuthModel->permissionGroupMenu($group->id)->result());
			}	

		}

		return $menu;
		
	}

	/**
	 * Dame el menu
	 *
	 * @param integer $id User id
	 *
	 * @return boolean menu del usuario
	 * @author Chununo
	 */
	public function getModules(int $id = 0)	{

		$this->ionAuthModel->triggerEvents('getModules');

		// Servicio de rutas
		$router = service('router');
		// Nombre del controlador, estructura: \APP_NAME\CONTROLADOR_NAME\METHOD_NAME
		$ruta_controlador = $router->controllerName();

		$ruta_controlador = explode("\\", $ruta_controlador);

		$controlador = $ruta_controlador[sizeof($ruta_controlador)-1];

		$metodo = $router->methodName();

		$groups = $this->ionAuthModel->getUsersGroups()->getResult();

		$menu = [];

		foreach ($groups as $i => $group) {

			if (sizeof($this->ionAuthModel->permissionGroupModule($group->id, $controlador.'/'.$metodo)->result()) > 0) {
				array_push($menu, ...$this->ionAuthModel->permissionGroupModule($group->id, $controlador.'/'.$metodo)->result());
			}	

		}

		return $menu;
		
	}

	/**
	 * Dame el menu
	 *
	 * @param integer $id User id
	 *
	 * @return boolean menu del usuario
	 * @author Chununo
	 */
	public function getDataModule(int $id = 0)	{

		$this->ionAuthModel->triggerEvents('getDataModule');

		// Servicio de rutas
		$router = service('router');
		// Nombre del controlador, estructura: \APP_NAME\CONTROLADOR_NAME\METHOD_NAME
		$ruta_controlador = $router->controllerName();

		$ruta_controlador = explode("\\", $ruta_controlador);

		$controlador = $ruta_controlador[sizeof($ruta_controlador)-1];

		$metodo = $router->methodName();

		$groups = $this->ionAuthModel->getUsersGroups()->getResult();

		$data = [];

		foreach ($groups as $i => $group) {

			$data = $this->ionAuthModel->dataModule('link',$controlador.'/'.$metodo);

			
		}

		return $data;
		
	}

	/**
	 * Dame las sucursale
	 *
	 * @param integer $id User id
	 *
	 * @return boolean menu del usuario
	 * @author Chununo
	 */
	public function getStores(int $id = 0)	{

		$id || $id = $this->session->get('user_id');

		$this->ionAuthModel->triggerEvents('getStores');

		$stores = $this->ionAuthModel->permissionStoreUser($id)->result();

		return $stores;
		
	}

	/**
	 * Dame las sucursale
	 *
	 * @param integer $id User id
	 *
	 * @return boolean menu del usuario
	 * @author Chununo
	 */
	public function getStoresID(int $id = 0)	{

		$id || $id = $this->session->get('user_id');

		$this->ionAuthModel->triggerEvents('getStoresID');

		$stores = $this->ionAuthModel->permissionStoreUser($id)->result();

		if (empty($stores)) {
			return [];
		}

		$ids = [];

		foreach($stores as $store){
			$ids[] = intval($store->store_id);
		}

		return $ids;
		
	}

	/**
	 * Dame los permisos por modulo
	 *
	 * @param integer $id User id
	 *
	 * @return boolean menu del usuario
	 * @author Chununo
	 */
	public function getActionModule(int $id = 0)	{

		$this->ionAuthModel->triggerEvents('getActionModule');

		// Servicio de rutas
		$router = service('router');
		// Nombre del controlador, estructura: \APP_NAME\CONTROLADOR_NAME\METHOD_NAME
		$ruta_controlador = $router->controllerName();

		$ruta_controlador = explode("\\", $ruta_controlador);

		$controlador = $ruta_controlador[sizeof($ruta_controlador)-1];

		$metodo = $router->methodName();

		$groups = $this->ionAuthModel->getUsersGroups()->getResult();

		$permissions = [];

		$actions = [];

		$result = [];

		$data = [];

		foreach ($groups as $i => $group) {

			if (sizeof($this->ionAuthModel->permissionGroup($group->id, $controlador.'/'.$metodo)->result()) > 0) {
				array_push($permissions, ...$this->ionAuthModel->permissionGroup($group->id, $controlador.'/'.$metodo)->result());
			}	
		}



		

		if (sizeof($permissions) > 0) {
			foreach ($permissions as $permission){
				$actions[$permission->actionID] = $permission->actionName;
				$data[$permission->actionID] = $permission;
			}
		}


		$result['permissions'] = $data;
		$result['actions'] = $actions;


		return $result;
	}


}
