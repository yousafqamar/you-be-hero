<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://youbehero.com
 * @since      1.0.0
 *
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/public
 * @author     Vasilis Kolip <bill@youbehero.com>
 */
class You_Be_Hero_Public {

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

                add_action('wp_head', function() {

                    $youbehero_data = get_option('ybh_donation_checkout_params');
                    if( !empty($youbehero_data) ){
                        $btn_color = $youbehero_data['widget_configurations']['checkout_page']['btn_color'] ?? "#3b82f6";
                        ?>

                    <style>
                        .donation-buttons button, .donation-buttons .radio-button {
                            border-color: <?php echo $btn_color?>;
                        }
                        .donation-buttons button:not(.selected), .donation-buttons .radio-button:not(.selected) {
                            color: <?php echo $btn_color?>;
                        }
                        .radio-button.selected {
                            background-color: <?php echo $btn_color?>;
                        }
                    </style>    
                        <?php
                    }
                });
            
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
		 * defined in You_Be_Hero_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The You_Be_Hero_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/you-be-hero-public.css', array(), $this->version, 'all' );

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
		 * defined in You_Be_Hero_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The You_Be_Hero_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
                if ( function_exists( 'is_checkout' ) && is_checkout() ) {
                    wp_enqueue_script(
                        'custom-checkout-fields',
                        plugin_dir_url( __FILE__ ) . 'js/custom-checkout.js',
//                        [ 'wp-element', 'wc-blocks-checkout' ], // Dependencies
                       [ 'lodash', 'react', 'wc-blocks-checkout', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n' ], // Ensure required dependencies
                        filemtime( plugin_dir_path( __FILE__ ) . 'js/custom-checkout.js' ),
                        true
                    );

                    // Add "type=module" attribute to the script
                    add_filter( 'script_loader_tag', function( $tag, $handle ) {
                        if ( 'custom-checkout-fields' === $handle ) {
                            return str_replace( 'src', 'type="module" src', $tag );
                        }
                        return $tag;
                    }, 10, 2 );
                }
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/you-be-hero-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * @return void
     */
    public function youbehero_fetch_api() {

        $api = new YouBeHero_API_Handler();

        // For GET API call
        $response = $api->get('donations', ['limit' => 10]);

        if (is_wp_error($response)) {
            echo 'Error: ' . $response->get_error_message();
        } else {
            print_r($response); // Process the response data
        }

        // // For POST API call
        $data = [
            'donation_amount' => 20,
            'user_id'         => 123,
            'cause_id'        => 456,
        ];

        $response = $api->post( 'donate', $data );

        if ( is_wp_error( $response ) ) {
            echo 'Error: ' . $response->get_error_message();
        } else {
            echo 'Donation successful!';
        }

    }
    
    
        // Register the block
        function donation_widget_register_block() {
            // Register the block using metadata from block.json
            register_block_type(YBH_PLUGIN_DIR . '/build');
        }

        // Enqueue scripts and styles
        function donation_widget_enqueue_scripts() {
            if (is_checkout()) {

                // For testing all widgets
                $ver = $_GET['ybh_update'] ?? '';

                // Fetch data from the API
                $data = $this->donation_widget_fetch_data( isset($_GET['ybh_update']), $ver );
                
                wp_enqueue_style('donation-widget-style', YBH_PLUGIN_URL.'assets/css/style.css');
                wp_enqueue_script('donation-widget-script', YBH_PLUGIN_URL.'assets/js/script.js', array('jquery'), null, true);
                
                if ($data) {
                    
                    // Extract causes and amounts
                    $causes = array_map(function ($cause) {
                        return [
                            'label' => $cause['name'],
                            'value' => $cause['id'],
                            'image' => $cause['image']
                        ];
                    }, $data['selected_causes']);

                    $amounts = array_values($data['donation_settings']['fixed_amounts']);

                    // Localize script with the data
                    wp_localize_script('donation-widget-script', 'ybh_donation_checkout_params', array(
                        'ajax_url' => admin_url('admin-ajax.php'),
                        'causes'   => $causes,
                        'amounts'  => $amounts,
                    ));
                    
                }
            }
        }
        
        // Add donation fee to cart
        function donation_widget_add_fee($cart) {
            $donation_amount = WC()->session->get('ybh_donation_amount', 0);
            $donation_cause = WC()->session->get('ybh_donation_cause', '');
            $donation_cause = WC()->session->get('_donation_org_name', '');
            $donation_cause_id = WC()->session->get('_donation_org_id', 0);
            $donation_cause_img = WC()->session->get('_donation_org_img', '');

            // Don't proceed in admin or if there's no donation amount
            if (is_admin() && !is_ajax()) {
                return;
            }

            // If amount is empty or zero, remove the fee and clear session
            if (empty($donation_amount) || floatval($donation_amount) <= 0) {
                $this->donation_widget_remove_fee();
                return;
            }

//            // Add fee if we have amount and cause
            if (!empty($donation_cause)) {
                $donation_amount = floatval($donation_amount);
                $donation_cause = sanitize_text_field($donation_cause);

                $fee_title = __('Donation for ', 'you-be-hero') . $donation_cause;
                $fee_id = $cart->add_fee($fee_title, $donation_amount);

                $last_fee_index = count($cart->fees) - 1;
                if (isset($cart->fees[$last_fee_index]) && $cart->fees[$last_fee_index]->id === $fee_id) {
                    $cart->fees[$last_fee_index]->_ybh_donation_amount = $donation_amount;
                    $cart->fees[$last_fee_index]->ybh_donation_cause = $donation_cause;
                    $cart->fees[$last_fee_index]->_donation_org_name = $donation_cause;
                    $cart->fees[$last_fee_index]->ybh_donation_cause_id = $donation_cause_id;
                    $cart->fees[$last_fee_index]->ybh_donation_cause_img = $donation_cause_img;
                }
            }
        }
        
        // Handle AJAX request
        function donation_widget_update_fee() {
            $org_id = absint($_POST['org_id']);
            $org_name = sanitize_text_field($_POST['org_name']);
            $amount = floatval($_POST['amount']);
            $org_img = sanitize_text_field($_POST['org_img']); // Changed from floatval to sanitize_text_field for image

            // Initialize cart if not exists
            if (!WC()->cart) {
                wc_load_cart();
            }

            // If amount is empty or zero, remove the fee
            if (empty($amount) || $amount <= 0) {
                $this->donation_widget_remove_fee();
                wp_send_json_success([
                    'fees' => WC()->cart->get_fees(),
                    'total' => WC()->cart->get_total('edit'),
                    'message' => 'Donation removed'
                ]);
                return;
            }

            // Validate required fields only if we're adding a fee
            if (empty($org_name) || empty($org_id)) {
                wp_send_json_error(['message' => 'Donation cause is not valid.']);
                return;
            }

            // Set session data
            WC()->session->set('ybh_donation_amount', $amount);
            WC()->session->set('ybh_donation_cause', $org_name);
            WC()->session->set('_donation_org_name', $org_name);
            WC()->session->set('_donation_org_id', $org_id);
            WC()->session->set('_donation_org_img', $org_img);

            // Trigger cart update to add/update the fee
//            WC()->cart->calculate_totals();

            wp_send_json_success([
                'fees' => WC()->cart->get_fees(),
                'total' => WC()->cart->get_total('edit'),
                'message' => 'Donation updated'
            ]);
        }
        
        function donation_widget_remove_fee() {
            WC()->session->set('ybh_donation_amount', 0);
            WC()->session->set('ybh_donation_cause', '');
            WC()->session->set('_donation_org_name', '');
            WC()->session->set('_donation_org_id', 0);
            WC()->session->set('_donation_org_img', '');
            if (!WC()->cart) {
                return;
            }

            $fees = WC()->cart->get_fees();

            foreach ($fees as $key => $fee) {
                if (isset($fee->ybh_donation_cause) || isset($fee->_ybh_donation_amount)) {
                    unset(WC()->cart->fees[$key]);
                }
            }
            if (WC()->cart) {
//                WC()->cart->calculate_totals();
            }
        }
        // final
        function woocommerce_checkout_create_order_fee_item($item, $fee_key, $fee, $order) {
            
            $donation_amount = WC()->session->get( 'ybh_donation_amount', 0 );
            $donation_cause = WC()->session->get( 'ybh_donation_cause', '' );
            $donation_org_name = WC()->session->get( '_donation_org_name', '' );
            $donation_cause_id = WC()->session->get( '_donation_org_id', 0 );
            $donation_cause_img = WC()->session->get( '_donation_org_img', '' );
            if (isset($donation_cause_id)) {
                $item->add_meta_data('_ybh_donation_amount', $donation_amount, true);
                $item->add_meta_data('_donation_org_id', $donation_cause_id, true);
                $item->add_meta_data('_donation_org_img', $donation_cause_img, true);
                $item->add_meta_data('Donation Organization', $donation_org_name, true);
                $item->add_meta_data('_donation_org_name', $donation_org_name, true);
                WC()->session->__unset('ybh_donation_amount');
                WC()->session->__unset('ybh_donation_cause');
                WC()->session->__unset('_donation_org_name');
                WC()->session->__unset('_donation_org_id');
                WC()->session->__unset('_donation_org_img');
            }
        }

        function donation_widget_fetch_data( $force_fetch = false, $ver ) {
            if( !$force_fetch ){
                $youbehero = get_option('ybh_donation_checkout_params', []);

                if( $youbehero && !empty($youbehero) ){
                    return $youbehero;
                }
            }
            
            $response = wp_remote_get('https://yousafqamar.com/ybh/youbehero-'.$ver.'.json'); // Replace with the actual API endpoint
            if (is_wp_error($response)) {
                return false;
            }

            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($data['data'])) {
                return false;
            }
            update_option( 'ybh_donation_checkout_params', $data['data'] );
            return $data['data'];
        }


        function save_custom_data_from_session($order, $data) {
            // Retrieve custom data from the session
            $ybh_donation_amount = WC()->session->get( 'ybh_donation_amount', 0 );
            $ybh_donation_cause = WC()->session->get( 'ybh_donation_cause', '' );
            $donation_org_name = WC()->session->get( '_donation_org_name', '' );
            $donation_org_id = WC()->session->get( '_donation_org_id', 0 );

            if ($ybh_donation_amount && $ybh_donation_cause ) {
                
                $item = new WC_Order_Item_Product();
                $item->set_name( __( $ybh_donation_cause, 'you-be-hero' ) ); // Custom item name
                $item->set_product_id( 0 ); // No actual product
                $item->set_subtotal( $ybh_donation_amount );
                $item->set_total( $ybh_donation_amount );
                $order->add_item( $item );

            }    
        }

//        function woocommerce_checkout_update_order_meta_fun($order_id, $data) {
//            // Retrieve custom data from the session
//            $ybh_donation_amount = WC()->session->get('ybh_donation_amount');
//            $ybh_donation_cause = WC()->session->get('ybh_donation_cause');
//
//            if ($ybh_donation_amount && $ybh_donation_cause ) {
//
//                // Add custom data to the order meta
//                $order_id = $order->get_id(); 
//                update_post_meta($order_id, '_ybh_donation_amount', $ybh_donation_amount);
//                update_post_meta($order_id, '_ybh_donation_cause', $ybh_donation_cause);
//        //        
//                // Clear the session data
//                WC()->session->__unset('ybh_donation_amount');
//                WC()->session->__unset('ybh_donation_cause');
//            }    
//        }

//        function woocommerce_get_order_item_totals_fun( $totals, $order ) {
//            $donation_cause = get_post_meta( $order->get_id(), '_ybh_donation_cause', true );
//        echo '$donation_cause: '.$donation_cause;die();
//            if ( ! empty( $donation_cause ) ) {
//                foreach ( $order->get_fees() as $fee ) {
//                    if ( strpos( $fee->get_name(), $donation_cause ) !== false ) {
//                        foreach ( $totals as $key => &$total ) {
//                            echo '$key: '.$key;
//                            if ( strpos( $total['label'], $fee->get_name() ) !== false ) {
//                                $total['label'] = __( 'Donation', 'you-be-hero' );
//                            }
//                        }
//                    }
//                }
//            }
//
//            return $totals;
//        }
    
    // Display shortcode at selected position
//    function ybh_display_donation_form() {
//        $shortcode = get_option('ybh_donation_shortcode', '[ybh_donation_form]');
//        echo do_shortcode($shortcode);
//    }
//
//    function ybh_insert_donation_form() {
//        $position = get_option('ybh_donation_position', 'woocommerce_after_checkout_billing_form');
//        if (has_action($position)) {
//            add_action($position, 'ybh_display_donation_form');
//        }
//    }

        /*not applied yet*/
    public function display_checkout_donation() {
        $this->youbehero_public_shortcodes();
        $checkout_page_id = get_option('woocommerce_checkout_page_id');
        if (!$checkout_page_id) return;

        // Retrieve the stored meta value
        $selected_position = get_post_meta($checkout_page_id, '_ybh_donation_position', true);
        $selected_position = 'woocommerce_before_checkout_payment';
//        var_dump('$selected_position');
//        var_dump($selected_position);
        if (empty($selected_position)) {
            $selected_position = 'woocommerce_after_checkout_billing_form'; // Default
        }

        // Add the donation form at the selected WooCommerce hook
        add_action($selected_position, function () {
            echo '<div class="ybh-donation-form">';
            echo '<h3>Support Us</h3>';
            echo do_shortcode('[donation_form]'); // Replace with actual shortcode
            echo '</div>';
        });
    }
    
    public function woocommerce_before_checkout_payment_fun($param) {
        echo do_shortcode('[donation_form]'); // Replace with actual shortcode
    }
    
    function ybh_register_checkout_meta() {
        register_post_meta('post', '_ybh_donation_position', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ));
    }
    
    /**
     * Function for public shortcodes
     * 
     * @return void
     */
    public function youbehero_public_shortcodes() {

        $shortcodes_class = new ShortCodes_Public();
        
    }

    /**
     * @param $order_id
     * @return void
     */
    public function send_api_on_order_complete($order_id) {
        if (!$order_id) {
            return;
        }

        $order = wc_get_order($order_id);

        $api_url = 'https://your-api-endpoint.com/webhook'; // Replace with your API endpoint

        $data = [
            'order_id'      => $order->get_id(),
            'total'         => $order->get_total(),
            'currency'      => $order->get_currency(),
            'customer_email'=> $order->get_billing_email(),
            'items'         => []
        ];

        // Get order items
        foreach ($order->get_items() as $item) {
            $data['items'][] = [
                'product_id' => $item->get_product_id(),
                'name'       => $item->get_name(),
                'quantity'   => $item->get_quantity(),
                'subtotal'   => $item->get_subtotal(),
            ];
        }

        // Send API request
        $response = wp_remote_post($api_url, [
            'method'    => 'POST',
            'body'      => json_encode($data),
            'headers'   => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer YOUR_ACCESS_TOKEN' // Add API authentication if required
            ]
        ]);

        // Log response (optional)
        if (is_wp_error($response)) {
            error_log('API Error: ' . $response->get_error_message());
        } else {
            error_log('API Response: ' . wp_remote_retrieve_body($response));
        }
    }
    
    function woocommerce_register_store_api_endpoints($endpoints) {
            $endpoints[] = [
                'namespace' => 'wc/store',
                'route' => '/youbehero',
                'callback' => function($request) {
                    try {
                        // Validate request
                        if (!wp_verify_nonce($request->get_header('X-WC-Store-API-Nonce'), 'wc_store_api')) {
                            throw new Exception('Invalid nonce', 403);
                        }
                        
                        // Process request
                        $params = $request->get_params();
                        
                        // Your custom logic here
                        $result = [
                            'success' => true,
                            'data' => [
                                'custom_field' => 'custom_value',
                                'params' => $params
                            ]
                        ];
                        
                        return new WP_REST_Response($result, 200);
                    } catch (Exception $e) {
                        return new WP_Error(
                            'youbehero_error',
                            $e->getMessage(),
                            ['status' => $e->getCode() ?: 400]
                        );
                    }
                },
                'methods' => ['GET', 'POST'],
                'permission_callback' => function() {
                    return current_user_can('read'); // Adjust capability as needed
                }
            ];
            return $endpoints;
        }

}
