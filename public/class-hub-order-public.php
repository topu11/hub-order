<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://touhidulislam.net
 * @since      1.0.0
 *
 * @package    Hub_Order
 * @subpackage Hub_Order/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Hub_Order
 * @subpackage Hub_Order/public
 * @author     Touhidul <touhidulislam256@gmail.com>
 */
class Hub_Order_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hub-order-public.css', array(), $this->version, 'all' );

		/** 
		
		   include datatable CSS
        */

		wp_enqueue_style( 'data-table-css', plugin_dir_url( __FILE__ ) . 'css/data-table-css.css', array(), $this->version, 'all' );

			/** 
		
		   include bootstrap CSS
        */
        if(is_page("manage-single-order") || is_page("manage-orders"))
		{

			wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap-css.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_localize_script($this->plugin_name, 'action_url_ajax', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			
		   ));
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hub-order-public.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( 'data-table-js', plugin_dir_url( __FILE__ ) . 'js/data-table-js.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'swal-alert-js', plugin_dir_url( __FILE__ ) . 'js/swal-alert.js', array( 'jquery' ), $this->version, false );

	}

}
