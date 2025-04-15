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

        function display_custom_fee_image_based_on_meta( $value, $item) {
//            
////            if( $value == 'Donation to Ένα παιδί μετράει τα άστρα (Look to the Stars)' ){
//////////                echo '<pre>';
//////////                var_dump($item->get_meta_data());
//////////                var_dump('_donation_org_img');
////                var_dump($item->get_type());
//////////                echo '</pre>';
////            }
//            $donation_org_id = $item->get_meta('_donation_org_id');
//            if( $donation_org_id  ){
//                $donation_org_img = $item->get_meta('_donation_org_img');
//
//                $donation_org_img = ( $donation_org_img ) ?$donation_org_img:'https://cdn.theorg.com/96d670ba-f440-464f-ac91-e156c79bb653_thumb.jpg';
//
//                    // Output the image
//                    $value = '<img src="' . esc_url( $donation_org_img ) . '" alt="'.$value.'" style="max-width:100px;" class="attachment-thumbnail size-thumbnail"/>'.$value;
//            }
//            return $value;
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
        
        function woocommerce_admin_order_totals_after_discount_fun($order_id) {
            $order = wc_get_order($order_id);
            $donation_total = 0;
            $other_fees_total = 0;

            foreach ($order->get_fees() as $fee) {
                $fee_total = (float) $fee->get_total(); // Ensure proper numeric type
//                echo '<pre>';
//                var_dump( $fee->get_meta('_ybh_donation_amount') );
//                echo '</pre>';
                if (stripos($fee->get_name(), 'donation') !== false) {
                    $donation_total += $fee_total;
                } else {
                    $other_fees_total += $fee_total;
                }
            }

            if ($donation_total > 0) {
                echo '<tr>';
                echo '<td class="label">' . __('Donation:', 'woocommerce') . '</td>';
                echo '<td width="1%"></td>';
                echo '<td class="total"><strong>' . wc_price($donation_total) . '</strong></td>';
                echo '</tr>';
            }

            if ($other_fees_total > 0) {
                echo '<tr>';
                echo '<td class="label">' . __('Other Fees:', 'woocommerce') . '</td>';
                echo '<td width="1%"></td>';
                echo '<td class="total"><strong>' . wc_price($other_fees_total) . '</strong></td>';
                echo '</tr>';
            }
        }
        
//            function woocommerce_order_item_meta_start($item_id, $item, $order) {
//                var_dump('$item_id');
//                var_dump($item_id);
//                if ($item->get_meta('Donation Organization ID')) {
//                    echo '<div class="donation-info">';
//                    echo 'Donation to: <strong>' . esc_html($item->get_meta('Donation Organization')) . '</strong>';
//                    echo ' (ID: ' . esc_html($item->get_meta('_donation_org_id')) . ')';
//                    echo '</div>';
//                }
//            }
            
//        function woocommerce_add_cart_item_data($cart_item_data, $product_id) {
//            if (isset($_POST['donation_org_id'])) {
//                $cart_item_data['donation_meta'] = [
//                    'org_id' => sanitize_text_field($_POST['donation_org_id']),
//                    'org_name' => sanitize_text_field($_POST['donation_org_name'])
//                ];
//            }
//            return $cart_item_data;
//        }
            
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

    /**
     * @return void
     */
    public function ybh_add_admin_menu() {
        $icon_url = plugin_dir_url(__FILE__) . 'img/ybh-dark-icon-20x20.png';
        add_menu_page(
            'YouBeHero API Settings',  // Page title
            'YouBeHero',               // Menu title
            'manage_options',          // Capability
            'ybh-settings',            // Menu slug
            array( $this, 'ybh_settings_page' ),       // Function to display content
            $icon_url, // Icon
            56                         // Position
        );
    }

    /**
     * @return void
     */
    public function ybh_settings_page() {

        $ybh_token = get_option( 'ybh_token' );

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/you-be-hero-api-settings.php';

    }

    /**
     * @return void
     * @throws Exception
     */
    public function ybh_get_token() {

        $token = bin2hex(random_bytes( 32 / 2 ) );
        update_option( 'ybh_token', sanitize_text_field( $token ) );
        wp_send_json( ['success' => true, 'token' => $token] );

//        $api_url = 'https://youbehero.com/shop/create-api-token';
//
//        $response = wp_remote_post($api_url, [
//            'method'  => 'POST',
//            'headers' => ['Content-Type' => 'application/json']
//        ]);
//
//        if (is_wp_error($response)) {
//            wp_send_json(['success' => false, 'message' => $response->get_error_message()]);
//        }
//
//        $body = json_decode(wp_remote_retrieve_body($response), true);
//
//        if (!empty($body['token'])) {
//            update_option('ybh_token', sanitize_text_field($body['token']));
//            wp_send_json(['success' => true, 'token' => $body['token']]);
//        } else {
//            wp_send_json(['success' => false, 'message' => 'Failed to retrieve token.']);
//        }

    }

}
