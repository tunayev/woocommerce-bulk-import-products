<?php

/**
 * Fired during plugin deactivation
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Bulk_Import_Products
 * @subpackage Bulk_Import_Products/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Bulk_Import_Products
 * @subpackage Bulk_Import_Products/includes
 * @author     Mustafa Tuna <mtunayev@gmail.com>
 */
class Bulk_Import_Products_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		delete_option('wc_wip_is_active');
	}
}
