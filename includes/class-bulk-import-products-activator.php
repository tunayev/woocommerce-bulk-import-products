<?php

/**
 * Fired during plugin activation
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Bulk_Import_Products
 * @subpackage Bulk_Import_Products/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bulk_Import_Products
 * @subpackage Bulk_Import_Products/includes
 * @author     Mustafa Tuna <mtunayev@gmail.com>
 */
class Bulk_Import_Products_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		delete_option('wc_wip_is_active'); /* remove if exists */

		add_option('wc_wip_is_active', 1); /* add new option */
	}
}
