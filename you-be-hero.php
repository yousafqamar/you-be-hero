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
// Plugin version.
define('YBH_VERSION', '1.0.0');

// Plugin base file.
define('YBH_PLUGIN_FILE', __FILE__);

// Plugin directory path.
define('YBH_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Plugin URL.
define('YBH_PLUGIN_URL', plugin_dir_url(__FILE__));

// Plugin basename.
define('YBH_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Text domain for translations.
define('YBH_TEXT_DOMAIN', 'you-be-hero');

// Plugin slug (useful for hooks and filters).
define('YBH_PLUGIN_SLUG', 'you-be-hero');

// Plugin name.
define('YBH_PLUGIN_NAME', 'YouBeHero');

// Plugin author.
define('YBH_PLUGIN_AUTHOR', 'Vasilis Kolip');

// Plugin author URI.
define('YBH_PLUGIN_AUTHOR_URI', 'https://youbehero.com/');

// Plugin URI.
define('YBH_PLUGIN_URI', 'https://youbehero.com');

// Plugin license details.
define('YBH_PLUGIN_LICENSE', 'GPL-2.0+');
define('YBH_PLUGIN_LICENSE_URI', 'http://www.gnu.org/licenses/gpl-2.0.txt');

// Directories for organized structure.
define('YBH_PLUGIN_LANG_DIR', YBH_PLUGIN_DIR . 'languages/');
define('YBH_PLUGIN_INCLUDES_DIR', YBH_PLUGIN_DIR . 'includes/');
define('YBH_PLUGIN_ADMIN_DIR', YBH_PLUGIN_DIR . 'admin/');
define('YBH_PLUGIN_PUBLIC_DIR', YBH_PLUGIN_DIR . 'public/');

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
