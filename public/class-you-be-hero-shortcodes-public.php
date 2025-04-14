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

class ShortCodes_Public {

    public function __construct( ) {


        add_shortcode('donation_form', [ $this, 'add_donation_form_shortcode' ]);
        add_shortcode('ybh_donation_form', [ $this, 'add_donation_form_shortcode' ]);
    }
    
    function add_donation_form_shortcode() {
        ob_start();
        include_once( __DIR__.'/../build/render.php' );
        return ob_get_clean();
    }

}
