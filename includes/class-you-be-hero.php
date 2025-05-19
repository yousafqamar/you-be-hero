<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://youbehero.com
 * @since      1.0.0
 *
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/includes
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
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/includes
 * @author     Vasilis Kolip <bill@youbehero.com>
 */
class You_Be_Hero {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      You_Be_Hero_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

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
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'YOU_BE_HERO_VERSION' ) ) {
			$this->version = YOU_BE_HERO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'you-be-hero';
                
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - You_Be_Hero_Loader. Orchestrates the hooks of the plugin.
	 * - You_Be_Hero_i18n. Defines internationalization functionality.
	 * - You_Be_Hero_Admin. Defines all hooks for the admin area.
	 * - You_Be_Hero_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-you-be-hero-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-you-be-hero-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-you-be-hero-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-you-be-hero-public.php';

        /**
         * The class responsible for defining all Shortcodes that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-you-be-hero-shortcodes-public.php';

        /**
         * The class responsible for defining all Helper functions
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers/class-you-be-hero-api-handler.php';

		$this->loader = new You_Be_Hero_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the You_Be_Hero_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new You_Be_Hero_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new You_Be_Hero_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'ybh_checkout_donation_register_settings' );
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin, 'ybh_enqueue_checkout_block_editor_assets' );
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin, 'ybh_donation_checkout_block_modifications' );
		$this->loader->add_action( 'woocommerce_admin_order_totals_after_discount', $plugin_admin, 'woocommerce_admin_order_totals_after_discount_fun' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

                $this->loader->add_action( 'admin_menu', $plugin_admin, 'ybh_add_admin_menu' );
                // Register setting to save token
                add_action( 'admin_init', function() {
                    register_setting( 'ybh_settings_group', 'ybh_token' );
                } );
                // Handle AJAX request to fetch API token
                $this->loader->add_action( 'wp_ajax_ybh_get_token', $plugin_admin,'ybh_get_token' );

                // Display in admin
                $this->loader->add_filter( 'woocommerce_order_item_get_name', $plugin_admin, 'display_custom_fee_image_based_on_meta', 10, 2 );
//            add_action('woocommerce_order_item_meta_start', $plugin_admin,'woocommerce_order_item_meta_start', 10, 3);
//            add_filter('woocommerce_add_cart_item_data', $plugin_admin, 'woocommerce_add_cart_item_data', 10, 2);

        }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new You_Be_Hero_Public( $this->get_plugin_name(), $this->get_version() );
                
		$this->loader->add_action( 'wp', $plugin_public, 'display_checkout_donation' );
//		$this->loader->add_action( 'woocommerce_before_checkout_payment', $plugin_public, 'woocommerce_before_checkout_payment_fun' );
		$this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'donation_widget_add_fee' );
		$this->loader->add_action( 'wp_ajax_update_donation_fee', $plugin_public, 'donation_widget_update_fee' );
		$this->loader->add_action( 'wp_ajax_nopriv_update_donation_fee', $plugin_public, 'donation_widget_update_fee' );
                $this->loader->add_action('woocommerce_checkout_create_order_fee_item', $plugin_public,'woocommerce_checkout_create_order_fee_item', 10, 4);
//        $this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'woocommerce_checkout_update_order_meta_fun', 10, 2 );
                $this->loader->add_action( 'woocommerce_checkout_create_order', $plugin_public, 'save_custom_data_from_session', 10, 2 );
//		$this->loader->add_filter( 'woocommerce_get_order_item_totals', $plugin_public, 'woocommerce_get_order_item_totals_fun', 10, 2 );

		$this->loader->add_action( 'init', $plugin_public, 'donation_widget_register_block' );
		$this->loader->add_action( 'init', $plugin_public, 'youbehero_public_shortcodes' );
		$this->loader->add_action( 'init', $plugin_public, 'ybh_register_checkout_meta' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'donation_widget_enqueue_scripts' );

//        $this->loader->add_action( 'woocommerce_order_status_completed', $plugin_public, 'ybh_send_api_on_order_complete', 10, 1 );
            
        // Try WooCommerce Blocks API first (modern approach)
//            $this->loader->add_action('woocommerce_blocks_loaded', $this, 'register_blocks_endpoint', 20);

        $this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'ybh_order_received_action' );
	}
        
        /*not applied due to dependency on woo block plugin, not all of the stores might have this addon*/
        public function register_blocks_endpoint() {
                $store_api_available = class_exists('WooCommerce\Blocks\Package') && 
                          class_exists('WooCommerce\Blocks\StoreApi\SchemasController') &&
                          class_exists('WooCommerce\Blocks\StoreApi\RoutesController');
    
            if (!$store_api_available) {
                error_log('YouBeHero: WooCommerce Store API classes not found');
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    add_action('admin_notices', function() {
                        echo '<div class="notice notice-error"><p>YouBeHero: WooCommerce Store API not available. Make sure WooCommerce Blocks is active.</p></div>';
                    });
                }
                return;
            }
            if (!function_exists('woocommerce_store_api_register_endpoint_data')) {
                error_log('YouBeHero: WooCommerce Store API not available');
                return;
            }

           require_once __DIR__ . '/class-youbehero-endpoint.php';

            // Register schema and route
            WooCommerce\Blocks\StoreApi\SchemasController::register(
                'youbehero', 
                'YouBeHero_Endpoint_Schema'
            );
            WooCommerce\Blocks\StoreApi\RoutesController::register(
                'youbehero',
                'YouBeHero_Endpoint_Route'
            );

            // Alternative registration method
            woocommerce_store_api_register_endpoint_data([
                'endpoint' => 'youbehero',
                'namespace' => 'wc/store',
            ]);
        }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    You_Be_Hero_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
