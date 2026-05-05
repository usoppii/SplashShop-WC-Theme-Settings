<?php
/**
 * Plugin Name:       ShopChop Theme Settings
 * Description:       Custom Elementor widgets and theme extensions for ShopChop.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            ShopChop
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       shopchop-theme-settings
 * Requires Plugins:  elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SHOPCHOP_SETTINGS_VERSION', '1.0.0' );
define( 'SHOPCHOP_SETTINGS_DIR', plugin_dir_path( __FILE__ ) );
define( 'SHOPCHOP_SETTINGS_URL', plugin_dir_url( __FILE__ ) );
