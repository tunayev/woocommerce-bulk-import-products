<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Bulk_Import_Products
 * @subpackage Bulk_Import_Products/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bulk_Import_Products
 * @subpackage Bulk_Import_Products/admin
 * @author     Mustafa Tuna <mtunayev@gmail.com>
 */
class Bulk_Import_Products_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $Bulk_Import_Products    The ID of this plugin.
	 */
	private $Bulk_Import_Products;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $Bulk_Import_Products       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($Bulk_Import_Products, $version)
	{

		$this->Bulk_Import_Products = $Bulk_Import_Products;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bulk_Import_Products_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bulk_Import_Products_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->Bulk_Import_Products, plugin_dir_url(__FILE__) . 'css/bulk-import-products-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bulk_Import_Products_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bulk_Import_Products_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->Bulk_Import_Products, plugin_dir_url(__FILE__) . 'js/bulk-import-products-admin.js', array('jquery'), $this->version, false);
	}
}
