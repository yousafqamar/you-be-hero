<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://youbehero.com
 * @since      1.0.0
 *
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    You_Be_Hero
 * @subpackage You_Be_Hero/includes
 * @author     Vasilis Kolip <bill@youbehero.com>
 */
class You_Be_Hero_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'you-be-hero',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
