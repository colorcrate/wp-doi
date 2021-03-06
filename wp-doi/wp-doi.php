<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           wp_doi
 *
 * @wordpress-plugin
 * Plugin Name:       WP DOI
 * Plugin URI:        http://example.com/wp-doi-uri/
 * Description:       Registers DOIs with Crossref.
 * Version:           0.0.0
 * Author:            Ian Hamilton
 * Author URI:        http://colorcrate.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-doi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-doi-activator.php
 */
function activate_wp_doi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-doi-activator.php';
	wp_doi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-doi-deactivator.php
 */
function deactivate_wp_doi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-doi-deactivator.php';
	wp_doi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_doi' );
register_deactivation_hook( __FILE__, 'deactivate_wp_doi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-doi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_doi() {

	$plugin = new wp_doi();
	$plugin->run();

}
run_wp_doi();
