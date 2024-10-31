<?php
/**
  * Plugin Name: Price Changer For WooCommerce
  * Version: 1.1.5
  * Description: Easily update prices across your entire WooCommerce store by applying a percentage increase/decrease or fixed amount adjustment to all products simultaneously, without altering the original prices listed in the product edit pages.
  * Author: Yakacj
  * Author URI: https://profiles.wordpress.org/yakacj/
  *  
  * Requires at least: 6.1
  * Tested up to: 6.6.2
  * 
  * WC requires at least: 6.0
  * WC tested up to: 9.3.2
  * Requires PHP: 7.4
  * 
  * License: GPLv3
  * License URI: https://www.gnu.org/licenses/gpl-3.0.html
  **/

    defined( 'ABSPATH' ) or exit;
    
    // Run only, if WooCommerce is installed and active
    if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;
    
	defined( 'YCPPC_FILE' ) or define( 'YCPPC_FILE', __FILE__  );
	defined( 'YCPPC_BASE' ) or define( 'YCPPC_BASE', plugin_basename( __FILE__ ) );
    
    require_once( 'init/class-plugin-init.php' );
    
    