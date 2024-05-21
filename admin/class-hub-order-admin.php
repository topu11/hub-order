<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://touhidulislam.net
 * @since      1.0.0
 *
 * @package    Hub_Order
 * @subpackage Hub_Order/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hub_Order
 * @subpackage Hub_Order/admin
 * @author     Touhidul <touhidulislam256@gmail.com>
 */
class Hub_Order_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hub_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hub_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hub-order-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( "bootstrap-admin-css", plugin_dir_url( __FILE__ ) . 'css/bootstrap-admin-css.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hub_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hub_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hub-order-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function custom_rest_end_point()
	{
		register_rest_route('hubstore/v1', '/create/order', array(
			'methods' => 'POST',
			'callback' => array($this,'create_order_in_hub')
		));
	}
    public  function create_order_in_hub()
	{
		return Hub_order_manage_orders::create_orders();
	}

	public function custom_admin_menu()
	{
		add_menu_page(
			'Hub Order Settings',        
			'Hub Order Settings',        
			'manage_options',         
			'hub-order-custom-settings-page',   
			array( $this, 'render_custom_settings_page' ),
			"",5
	  
		);
		add_submenu_page('options.php', 'Shop Update', 'Shop Update', 'manage_options', 'hub-order-shop-update', array( $this, 'hub_order_shop_update' ));

   		add_submenu_page('hub-order-custom-settings-page', 'Add new Shop', 'Add new Shop', 'manage_options', 'hub-order-shop-create', array( $this, 'hub_order_shop_create' ));
	}
	public function render_custom_settings_page()
	{
		require_once( dirname( __FILE__ ).'/partials/settings/index.php' );
	}
	public function custom_order_cpt()
	{
		Hub_order_custom_post_role_manage::custom_role_post_create();
	}
	public function register_shortcodes()
	{
		
		add_shortcode( 'manage_oders_functionalities', array( $this, 'manage_oders_functionalities') );
		add_shortcode( 'manage_oders_single', array( $this, 'manage_oders_single') );
	}
	public function manage_oders_functionalities()
	{
		return Hub_order_manage_orders::show_orders_table();
	}
	public function manage_oders_single()
	{
		return Hub_order_manage_orders::manage_oders_single();
	}
	public function add_order_notes()
	{
		echo Hub_order_manage_orders::add_order_notes();
		
		wp_die();
	}
	public function change_order_status()
	{
		echo Hub_order_manage_orders::change_order_status();
		
		wp_die();
	}
	public function hub_order_shop_create()
	{
		require_once( dirname( __FILE__ ).'/partials/settings/create.php' );
	}
	public function hub_order_shop_update()
	{
		require_once( dirname( __FILE__ ).'/partials/settings/update.php' );
	}
	public function order_json_update()
	{
		$cache_path=dirname( __FILE__,2).'/includes/order-data-json-cache.js';
        $order_data_database=Hub_order_manage_orders::order_table_gerenator();
		
        file_put_contents($cache_path,json_encode($order_data_database));
        echo json_encode([
			'success'=>'success',
		]);
       wp_die();
	}
}
