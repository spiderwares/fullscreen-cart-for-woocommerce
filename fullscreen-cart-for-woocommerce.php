<?php
/**
 * Plugin Name: Fullscreen Cart For WooCommerce
 * Plugin URI: https://www.jthemes.com/
 * Description: A plugin that enhances the WooCommerce shopping experience by displaying a fullscreen cart overlay along with interface quick add to cart.
 * Version: 1.1.6
 * Author: Jthemes Studio
 * Author URI: https://www.jthemes.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: fullscreen-cart-for-woocommerce
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'FCWC_FILE' ) ) :
	define( 'FCWC_FILE', __FILE__ ); // Define the plugin file path.
endif;

if ( ! defined( 'FCWC_BASENAME' ) ) :
	define( 'FCWC_BASENAME', plugin_basename( FCWC_FILE ) ); // Define the plugin basename.
endif;

if ( ! defined( 'FCWC_VERSION' ) ) :
	define( 'FCWC_VERSION', '1.1.6' ); // Define the plugin version.
endif;

if ( ! defined( 'FCWC_PATH' ) ) :
	define( 'FCWC_PATH', plugin_dir_path( __FILE__ ) ); // Define the plugin directory path.
endif;

if ( ! defined( 'FCWC_TEMPLATE_PATH' ) ) :
	define( 'FCWC_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . '/templates/' ); // Define the plugin directory path.
endif;

if ( ! defined( 'FCWC_URL' ) ) :
	define( 'FCWC_URL', plugin_dir_url( __FILE__ ) ); // Define the plugin directory URL.
endif;

if ( ! defined( 'FCWC_UPGRADE_URL' ) ) :
	define( 'FCWC_UPGRADE_URL', 'https://www.spiderwares.com/' ); // Define the upgrade URL.
endif;

if ( ! class_exists( 'FCWC_Fullscreen_Cart', false ) ) :
	include_once FCWC_PATH . '/includes/class-fcwc-fullscreen-cart.php';
endif;

if ( ! defined( 'FCWC_PRO_VERSION' ) ) :
	define( 'FCWC_PRO_VERSION', 'https://codecanyon.net/item/full-screen-interactive-cart-for-woocommerce/56536285' ); // Define the upgrade URL.
endif;

FCWC_Fullscreen_Cart::instance();
register_activation_hook( __FILE__, [ FCWC_Fullscreen_Cart::class, 'activate'] );