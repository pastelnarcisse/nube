<?php
namespace Config;

/**
 * Name:    Nube Narcisse
 *
 * Created:  10.11.2021
 *
 * Description:  Basado en el Ion Auth, se usa el tipo de configuración
 *               Sus modificaciones son esenciales para la confiracion del BI
 *
 * Requirements: PHP7.2 or above
 *
 * @package    CodeIgniter-Ion-Auth
 * @author     Erick Aguirre Mtz <chununo@gmail.com>
 * @filesource
 */

/**
 * Configuration file for Ion Auth
 *
 * @package CodeIgniter-NubeNarcisse
 */
class NubeNarcisse extends \CodeIgniter\Config\BaseConfig
{

	/**
	 * Database group name option.
	 * -------------------------------------------------------------------------
	 * Allows to select a specific group for the database connection
	 *
	 * Default is empty: uses default group defined in CI's configuration
	 * (see application/config/database.php, $active_group variable)
	 *
	 * @var string
	 */
	public $databaseGroupName = 'historico';
	public $databaseGroupCloud = 'nube';

	/**
	 * Tables (Database table names)
	 *
	 * @var array
	 */
	public $tables = [
		'venta_diaria'          	=> 'v_venta_diaria',
		'venta_diaria_sucursal'		=> 'v_venta_diaria_sucursal',
		'sale_general'				=> 'v_sale_general',
		'sale_products'				=> 'v_sale_products',
		'department'				=> 'departamento',
		'category'					=> 'categoria',
		'stock'						=> 'v_stockStore',
		'stockCategory'				=> 'v_stockCategory',
		'store'						=> 'store'
	];

	/**
	 * Users table column and Group table column you want to join WITH.
	 * Joins from users.id
	 * Joins from groups.id
	 *
	 * @var array
	 */
	public $join = [
		'users'  => 'user_id',
		'groups' => 'group_id',
	];


	/**
	 * Specifies the views that are used to display the
	 * errors and messages.
	 *
	 * @var array
	 */
	public $templates = [

		// templates for errors cf : https://bcit-ci.github.io/CodeIgniter4/libraries/validation.html#configuration
		'errors'   => [
			'list' => 'list',
		],

		// templates for messages
		'messages' => [
			'list'   => 'App\Views\Messages\list',
			'single' => 'App\Views\Messages\single',
		],
	];
}
