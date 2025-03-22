<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://youbehero.com
 * @since      1.0.0
 *
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/admin
 * @author     Vasilis Kolip <bill@youbehero.com>
 */
class You_Be_Hero_Admin {

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
        
        function ybh_enqueue_checkout_block_editor_assets() {
            wp_enqueue_script(
                'ybh-checkout-block-settings',
                plugins_url('js/checkout-block-settings.js', __FILE__),
                array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-data', 'wp-compose', 'wc-blocks-checkout'),//'wp-element', 'wc-blocks-checkout'
                filemtime(YBH_PLUGIN_ADMIN_DIR . 'js/checkout-block-settings.js'),
                true
            );
        }
        
        // Register settings
        function ybh_checkout_donation_register_settings() {
            register_setting('ybh_checkout_donation_settings', 'ybh_donation_shortcode');
            register_setting('ybh_checkout_donation_settings', 'ybh_donation_position');
        }

        function ybh_donation_checkout_block_modifications() {
//            die(YBH_PLUGIN_URL.'admin/js/checkout-widget.js');
            wp_enqueue_script(
                'custom-checkout-widget',
                YBH_PLUGIN_URL.'admin/js/checkout-widget.js',
                array('wp-blocks', 'wp-edit-post', 'wp-hooks'),
                filemtime(YBH_PLUGIN_ADMIN_DIR . '/js/checkout-widget.js')
            );
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
		 * defined in You_Be_Hero_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The You_Be_Hero_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/you-be-hero-admin.css', array(), $this->version, 'all' );

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
		 * defined in You_Be_Hero_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The You_Be_Hero_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/you-be-hero-admin.js', array( 'jquery' ), $this->version, false );

	}

}
