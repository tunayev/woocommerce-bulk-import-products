<?php

/**
 * The file that defines the core plugin class
 *
 * 
 *
 * @link       https://github.com/tunayev/woocommerce-bulk-import-products
 * @since      1.0.0
 *
 * @package    Bulk_Import_Products
 * @subpackage Bulk_Import_Products/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bulk_Import_Products
 * @subpackage Bulk_Import_Products/includes
 * @author     Mustafa Tuna <mtunayev@gmail.com>
 */
class Bulk_Import_Products
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bulk_Import_Products_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $Bulk_Import_Products    The string used to uniquely identify this plugin.
	 */
	protected $Bulk_Import_Products;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Use a singleton pattern to instantiate the plugin.
	 *
	 * @since    1.0.0
	 */

	protected $tagExists;

	protected $catExists;

	protected static $instance;

	public static function get_instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct()
	{
		if (defined('Bulk_Import_Products_VERSION')) {
			$this->version = Bulk_Import_Products_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->Bulk_Import_Products = 'bulk-import-products';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->tagExists = false;
		$this->catExists = false;

		add_action('admin_menu', array($this, 'add_menu'));
		add_action('wp_ajax_import_json_products', array($this, 'import_json_products'));
		add_filter('upload_mimes', array($this, 'allow_json_upload'));
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bulk_Import_Products_Loader. Orchestrates the hooks of the plugin.
	 * - Bulk_Import_Products_i18n. Defines internationalization functionality.
	 * - Bulk_Import_Products_Admin. Defines all hooks for the admin area.
	 * - Bulk_Import_Products_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bulk-import-products-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bulk-import-products-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-bulk-import-products-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-bulk-import-products-public.php';

		$this->loader = new Bulk_Import_Products_Loader();
	}

	/**
	 * allow_json_upload
	 * Use text/plain instead of application/json because of a bug in the WordPress core
	 *
	 * @param  mixed $mime_types
	 * @return void
	 */
	public function allow_json_upload($mime_types)
	{
		$mime_types['json'] = 'text/plain'; //Adding json extension
		return $mime_types;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bulk_Import_Products_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Bulk_Import_Products_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Bulk_Import_Products_Admin($this->get_Bulk_Import_Products(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Bulk_Import_Products_Public($this->get_Bulk_Import_Products(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_Bulk_Import_Products()
	{
		return $this->Bulk_Import_Products;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bulk_Import_Products_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * Add menu page to admin
	 *
	 * @return void
	 */
	public function add_menu()
	{
		add_menu_page(
			'Import JSON',
			'Import JSON Products',
			'manage_options',
			'import-json-products',
			array($this, 'echo_import_json_products_page'),
			'dashicons-upload',
			100
		);
	}

	public function echo_import_json_products_page()
	{
?>
		<div class="wrap">
			<h1>Import JSON Products</h1>
			<form id="import-json-products-form" method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-ajax.php'); ?>">
				<input type="hidden" name="action" value="import_json_products" />
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('import_json_products'); ?>" />
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="json-file">JSON File</label>
							</th>
							<td>
								<input type="file" name="json-file" id="json-file" />
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Import" />
				</p>
			</form>
			<div id="import-json-products-result"></div>
		</div>
<?php
		add_action('wp_ajax_file_upload', array($this, 'file_upload_callback'));
	}

	public function file_upload_callback()
	{
		check_ajax_referer('import_json_products', 'nonce');
		$arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
		if (true || in_array($_FILES['json-file']['type'], $arr_img_ext)) {
			$upload = wp_upload_bits($_FILES["json-file"]["name"], null, file_get_contents($_FILES["json-file"]["tmp_name"]));

			return $upload['url'];
		}
	}

	/**
	 * import_json_products
	 *
	 * @return void
	 */
	public function import_json_products()
	{
		check_ajax_referer('import_json_products', 'nonce');
		$jsonFile = $_FILES['json-file'];
		// Upload the file
		$file = wp_upload_bits($jsonFile['name'], null, file_get_contents($jsonFile['tmp_name']));
		$products = json_decode(file_get_contents($file['file']), true);
		//		print_r($products['data']);
		//chunk the products array into 10
		$products = $products['data'];

		foreach ($products as $product) {
			$this->create_variable_product($product);
		}

		wp_redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * create_product_images
	 *
	 * @param  mixed $product_id
	 * @param  mixed $images
	 * @return void
	 */
	public function create_product_images($product_id, $images)
	{
		$image_ids = array();
		foreach ($images as $image) {
			$image_ids[] = $this->create_product_image($product_id, $image);
		}
		update_post_meta($product_id, '_product_image_gallery', implode(',', $image_ids));
	}

	/**
	 * create_product_image
	 *
	 * @param  mixed $product_id
	 * @param  mixed $image
	 * @return void
	 */
	public function create_product_image($product_id, $image)
	{
		$image_url = $image['src'];
		$image_name = basename($image_url);
		$upload_dir = wp_upload_dir();
		$image_data = file_get_contents($image_url);
		$filename = wp_unique_filename($upload_dir['path'], $image_name);
		if (wp_mkdir_p($upload_dir['path'])) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}
		file_put_contents($file, $image_data);
		$wp_filetype = wp_check_filetype($filename, null);
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => sanitize_file_name($filename),
			'post_content' => '',
			'post_status' => 'inherit'
		);
		$attach_id = wp_insert_attachment($attachment, $file, $product_id);
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata($attach_id, $file);
		wp_update_attachment_metadata($attach_id, $attach_data);
		return $attach_id;
	}

	/**
	 * create_variable_product
	 *
	 * @param  mixed $args
	 * @return void
	 */

	public function create_variable_product($args)
	{
		$defaults = array(
			'post_title' => $args['title'],
			'post_content' => '',
			'post_status' => 'publish',
			'post_type' => 'product',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'menu_order' => 0,
			'post_excerpt' => '',
			'post_author' => 1,
			'post_password' => '',
			'post_parent' => '',
			'post_name' => '',
			'post_date' => '',
			'post_date_gmt' => '',
			'post_modified' => '',
			'post_modified_gmt' => '',
			'guid' => '',
			'import_id' => 0,
			'context' => '',
		);
		$args = wp_parse_args($args, $defaults);
		$product_id = wp_insert_post($args);
		// Set the product type
		wp_set_object_terms($product_id, 'variable', 'product_type');
		// Set the product SKU
		$args['sku'] ? update_post_meta($product_id, '_sku', $args['sku']) : '';
		// Set the product price
		update_post_meta($product_id, '_regular_price', $args['price']);
		// Set the product stock
		$args['stock'] ? update_post_meta($product_id, '_stock', $args['stock']) : '';
		// Set the product stock status
		$args['stock_status'] ? update_post_meta($product_id, '_stock_status', $args['stock_status']) : '';
		// Set the product visibility
		$args['visibility'] ? update_post_meta($product_id, '_visibility', $args['visibility']) : '';
		update_post_meta($product_id, '_visibility', $args['visibility']);
		// Set the product weight
		$args['weight'] ? update_post_meta($product_id, '_weight', $args['weight']) : '';
		// Set the product length
		$args['length'] ? update_post_meta($product_id, '_length', $args['length']) : '';
		// Set the product width
		$args['width'] ? update_post_meta($product_id, '_width', $args['width']) : '';
		// Set the product height
		$args['height'] ? update_post_meta($product_id, '_height', $args['height']) : '';
		// Set the product shipping class
		$args['shipping_class'] ? wp_set_object_terms($product_id, $args['shipping_class'], 'product_shipping_class') : '';

		// Set the product categories		
		$args['wp_category_id'] ? wp_set_object_terms($product_id, $args['wp_category_id'], 'product_cat') : '';

		// Check if the product tag exists in WordPress tags
		if ($args['store']) {
			if (!term_exists($args['store'], 'product_tag')) {
				$term = get_term_by('name', $args['store'], 'product_tag');
				if ($term) {
					wp_set_object_terms($product_id, $term->term_id, 'product_tag');
				} else {
					// Create the product tag in WordPress
					$term = wp_insert_term($args['store'], 'product_tag');
					wp_set_object_terms($product_id, $term['term_id'], 'product_tag');
				}
			}
		}

		$args['store'] ? wp_set_object_terms($product_id, $args['store'], 'product_tag') : '';

		$attributes = array();
		$product = wc_get_product($product_id);
		$attribute = new WC_Product_Attribute();
		$attribute->set_id(wc_attribute_taxonomy_id_by_name('pa_size'));
		$attribute->set_name('pa_size');
		$attribute->set_options($args['sizes']);
		$attribute->set_position(0);
		$attribute->set_visible(true);
		$attribute->set_variation(true);
		$attributes[] = $attribute;
		$product->set_attributes($attributes);
		$product->save();
		$this->update_meta($product_id, $args['meta_data']);


		foreach ($args['sizes'] as $size) {
			$variation_data = [
				'attributes' => [
					'size' => $size,
				],
				'regular_price' => $args['price'],
				'sale_price' => $args['sale_price'] ?: '',
				'stock_quantity' => $args['stock_quantity'] ?: '',
			];
			$this->create_variation($product_id, $variation_data);
		}
	}

	public function update_meta($product_id, $attributes)
	{
		foreach ($attributes as $attribute) {
			update_post_meta($product_id, $attribute['key'], $attribute['value']);
		}
	}


	/**
	 * Create a product variation for a defined variable product ID.
	 *
	 * @since 3.0.0
	 * @param int   $product_id | Post ID of the product parent variable product.
	 * @param array $variation_data | The data to insert in the product.
	 */

	public function create_product_variation($product_id, $variation_data)
	{
		// Get the Variable product object (parent)
		$product = wc_get_product($product_id);

		$variation_post = array(
			'post_title'  => $product->get_name(),
			'post_name'   => 'product-' . $product_id . '-variation',
			'post_status' => 'publish',
			'post_parent' => $product_id,
			'post_type'   => 'product_variation',
			'guid'        => $product->get_permalink()
		);

		// Creating the product variation
		$variation_id = wp_insert_post($variation_post);

		// Get an instance of the WC_Product_Variation object
		$variation = new WC_Product_Variation($variation_id);

		// Iterating through the variations attributes
		foreach ($variation_data['attributes'] as $attribute => $term_name) {
			$taxonomy = 'pa_' . $attribute; // The attribute taxonomy

			// If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
			if (!taxonomy_exists($taxonomy)) {
				register_taxonomy(
					$taxonomy,
					'product_variation',
					array(
						'hierarchical' => false,
						'label' => ucfirst($attribute),
						'query_var' => true,
						'rewrite' => array('slug' => sanitize_title($attribute)), // The base slug
					),
				);
			}

			// Check if the Term name exist and if not we create it.
			if (!term_exists($term_name, $taxonomy))
				wp_insert_term($term_name, $taxonomy); // Create the term

			$term_slug = get_term_by('name', $term_name, $taxonomy)->slug; // Get the term slug

			// Get the post Terms names from the parent variable product.
			$post_term_names =  wp_get_post_terms($product_id, $taxonomy, array('fields' => 'names'));

			// Check if the post term exist and if not we set it in the parent variable product.
			if (!in_array($term_name, $post_term_names))
				wp_set_post_terms($product_id, $term_name, $taxonomy, true);

			// Set/save the attribute data in the product variation
			update_post_meta($variation_id, 'attribute_' . $taxonomy, $term_slug);
		}

		## Set/save all other data

		// SKU
		if (!empty($variation_data['sku']))
			$variation->set_sku($variation_data['sku']);

		// Prices
		if (empty($variation_data['sale_price'])) {
			$variation->set_price($variation_data['regular_price']);
		} else {
			$variation->set_price($variation_data['sale_price']);
			$variation->set_sale_price($variation_data['sale_price']);
		}
		$variation->set_regular_price($variation_data['regular_price']);

		// Stock
		if (!empty($variation_data['stock_qty'])) {
			$variation->set_stock_quantity($variation_data['stock_qty']);
			$variation->set_manage_stock(true);
			$variation->set_stock_status('');
		} else {
			$variation->set_manage_stock(false);
		}

		$variation->set_weight(''); // weight (reseting)

		$variation->save(); // Save the data
	}

	/**
	 * Create a product variation for a defined variable product ID. 
	 *
	 * @param  mixed $product_id
	 * @param  mixed $variation_data
	 * @return void
	 */
	public function create_variation($product_id, $variation_data)
	{
		$variation = new WC_Product_Variation();
		$variation->set_parent_id($product_id);
		$variation->set_attributes(array(wc_attribute_taxonomy_id_by_name('pa_size') => $variation_data['attributes']['size']));
		$variation->set_regular_price($variation_data['regular_price']);
		$variation->save();

		$variationnew = new \WC_Product_Variation($variation->get_id());
		update_post_meta($variation->get_id(), 'attribute_pa_size', sanitize_title($variation_data['attributes']['size']));
		$variationnew->save();
	}
}
