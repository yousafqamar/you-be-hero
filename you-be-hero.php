<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://youbehero.com
 * @since             1.0.0
 * @package           You_Be_Hero
 *
 * @wordpress-plugin
 * Plugin Name:       YouBeHero
 * Plugin URI:        https://youbehero.com
 * Description:       YouBeHero is a powerful WordPress plugin that seamlessly integrates with WooCommerce, allowing store owners to enable a donation system at checkout and product pages. Customers can contribute to nonprofit organizations directly during their shopping experience.
With dynamic widgets, shortcodes, and API-powered configurations, YouBeHero ensures a customizable and smooth donation process.
 * Version:           1.0.0
 * Author:            Vasilis Kolip
 * Author URI:        https://youbehero.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       you-be-hero
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'YOU_BE_HERO_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-you-be-hero-activator.php
 */
function activate_you_be_hero() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-you-be-hero-activator.php';
	You_Be_Hero_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-you-be-hero-deactivator.php
 */
function deactivate_you_be_hero() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-you-be-hero-deactivator.php';
	You_Be_Hero_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_you_be_hero' );
register_deactivation_hook( __FILE__, 'deactivate_you_be_hero' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-you-be-hero.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_you_be_hero() {

	$plugin = new You_Be_Hero();
	$plugin->run();

}
run_you_be_hero();
