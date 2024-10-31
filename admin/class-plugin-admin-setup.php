<?php

/**
 * Plugin admin setup class
 */

namespace YCPlugins\PriceChanger\Admin;
use YCPlugins\PriceChanger\Init\Utils\Plugin_Utils as Utils;

defined( 'ABSPATH' ) or exit;

class Admin_Setup{

    protected static $_instance = null;
    protected static $_section 	= 'price_changer_sec';
    
    public function __construct(){
        \add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
        \add_action( 'admin_menu', [ $this, 'admin_menu_callback' ] );
        \add_action( 'admin_init', [ $this, 'register_settings' ] );
        \add_filter( 'plugin_action_links_' . YCPPC_BASE, [ $this, 'plugin_links' ] );
        \add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
        
    }

    public function admin_scripts(){
        wp_enqueue_style( 'ycppc-admin-style', plugins_url( '/css/ycppc-admin.css', __FILE__ ), array() );
        wp_enqueue_style( 'ycppc-select2', plugins_url( '/css/ycppc-select2.min.css', __FILE__ ), array() );
        wp_enqueue_script( 'ycppc-select2-js', plugins_url( '/js/ycppc-select2.min.js', __FILE__ ), array( 'jquery') );
        wp_enqueue_script( 'ycppc-select-js', plugins_url( '/js/ycppc-select2.js', __FILE__ ), array( 'jquery') );
    }
    
    public function admin_menu_callback() {
        add_submenu_page( 'woocommerce', 'Price Changer', 'Price Changer', 'manage_options', 'price-changer', [ $this, 'admin_page_contents' ] ); 
    }

    public function admin_page_contents() {
        $value  = self::get_value('show_ycp_plugins');
        $dash_a = $value ? 'dashicons-arrow-down' : 'dashicons-arrow-up';
        $show_a = $value ? 'display:none;' : '';
    	?>
    	<style>.notice-warning{ display:none;}</style>
		<div class="wrap">
			<div class="ycpr-grid-container">
			    <div class="ycpr-grid-child">
                    <div class="ycpr-title"><?php echo esc_html( get_admin_page_title() ); ?></div>
                </div>
			    <div class="yc-pro-link">
			        <a class="ycp-proo" href="//ycplugins.com/item/price-changer-pro-for-woocommerce-plugin/" target="_blank">Check Pro Features</a>
			    </div>
            </div>
			<form method="post" action="options.php">
                <?php
                    settings_errors();
				    settings_fields( 'price_changer_settings' );
					do_settings_sections( 'price_changer' );
					submit_button();
			    ?>
		</div>
		<?php if( time() <= strtotime( '11:59:59pm September 30 2024') ):?>
		<div class="sep-offer" style="background: #5c905c;color: #fff;padding: 10px;border-radius: 4px;max-width: 500px;margin-right:12px;">
		    <div style="font-size: 17px;font-weight: 600;">Special Promotion: <a href="https://ycplugins.com/item/price-changer-pro-for-woocommerce-plugin/" target="_blank" style="color:#fff;">Upgrade to Pro</a></div>
		    <div style="font-size: 15px;margin-top: 10px;">For a limited time, till this September 30, enjoy 15% off on Pro upgrade.<br> Use discount code <b>SEP15</b> at checkout to get 15% flat discount.</div>
		</div>
		<?php endif;?>
		<div class="ycpr-other-free">
		    <h2 class="ycpr-other-title"><label for="show_ycp_plugins" class="olbl">Our other free plugins <span class="dashicons <?php echo esc_attr( $dash_a );?>"></span><input type="checkbox" class="show_ycp_plugins" id="show_ycp_plugins" name="show_ycp_plugins" value="1" <?php checked( $value, 1 );?>></label></h2>
		    </form>
		    <div class="ycpr-list-sec" style="<?php echo esc_attr( $show_a );?>" >
		    <div class="ycpr-plugin-sec">
		        <a href="https://wordpress.org/plugins/variation-auto-expire-for-woocommerce/" target="_blank">
		            <div class="ycpr-pl-data">
		                <img src="https://ps.w.org/variation-auto-expire-for-woocommerce/assets/icon-256x256.jpg?rev=2851770" width="64">
		                <div class="ycpr-plugin-title">Variation Auto Expire For WooCommerce</div>
		            </div>
		        </a>
		    </div>
		    <div class="ycpr-plugin-sec">
		        <a href="https://wordpress.org/plugins/new-custom-order-columns/" target="_blank">
		            <div class="ycpr-pl-data">
		                <img src="https://ps.w.org/new-custom-order-columns/assets/icon-256x256.png?rev=3031500" width="64">
		                <div class="ycpr-plugin-title">New Custom Order Columns</div>
		            </div>
		        </a>
		    </div>
		    </div>
	    </div>
	    <?php
    }
    
    public function register_settings(){
	    $page_slug    = 'price_changer';
	    $option_group = 'price_changer_settings';
        
	    add_settings_section( self::$_section, '', '', $page_slug );
	    
        foreach( Utils::get_fields() as $field => $label ){
            register_setting( $option_group, $field );
            add_settings_field( $field, $label, [ $this, $field.'_callback' ], $page_slug, self::$_section );
        }
		register_setting( $option_group, 'show_ycp_plugins' );
    }
    
    private static function get_value( $function ){
        return Utils::get_value( $function );
    }
    
    public function yc_price_action_callback() {
        $value = self::get_value( __FUNCTION__ );
	    ?>
		<label><input type="radio" name="yc_price_action" id="price_up" value="priceup" <?php checked( $value, 'priceup');?> /><span style="color:#008000;"> ▲</span> Increase</label><br/><br/>
		<label><input type="radio" name="yc_price_action" id="price_down" value="pricedown" <?php checked( $value, 'pricedown');?> /><span style="color:#b51d1d;"> ▼</span> Decrease</label>
	    <?php
    }
    
    public function yc_price_change_type_callback(){
        $value = self::get_value( __FUNCTION__ );
	    ?>
		<label><input type="radio" name="yc_price_change_type" id="price_perc" value="perc" <?php checked( $value, 'perc');?> /><span style="color:#155e82;font-weight:bold;"> &#x25;</span> Percentage</label><br/><br/>
		<label><input type="radio" name="yc_price_change_type" id="price_amt" value="amount" <?php checked( $value, 'amount');?> /><span style="color:#155e82;font-weight:bold;"> &#x24;</span> Amount</label>
	    <?php
    }
    
    public function yc_price_change_value_callback() {
        $value = self::get_value( __FUNCTION__ );
	    ?>
		<label><input type="number" name="yc_price_change_value" style="min-width:100px;" step="0.1" value="<?php echo esc_attr( $value );?>" min="0" /></label>
	    <?php
    }

    public function yc_price_rounded_callback(){
        $value = self::get_value( __FUNCTION__ );
        ?>
        <label><input type="checkbox" name="yc_price_rounded" value="yes" <?php checked( $value, 'yes' ); ?> /> If checked, prices will be rounded</label>
	    <?php
    }
    
    public function yc_exclude_products_callback( $args ){
        $ex_products = (array) self::get_value( __FUNCTION__ );
        ?>
        <label>
            <select multiple id="yc_exclude_products" name="yc_exclude_products[]">
                <?php foreach( Utils::get_products() as $product ):?>
                    <option value="<?php echo esc_attr( $product->ID );?>" <?php selected( in_array( $product->ID, $ex_products ), true );?>><?php echo esc_attr( $product->post_title );?>
                    </option>
                <?php endforeach;?>
            </select>
        </label>
        <?php
    }
    
    private static function get_plugin_links(){
        return array(
		    '<a href="' . admin_url( 'admin.php?page=price-changer' ) . '">Changer</a>',
		    '<a class="ycp-pro" href="//ycplugins.com/item/price-changer-pro-for-woocommerce-plugin/" target="_blank">Check Pro Features</a>',
	    );
    }
    
    private static function get_meta_links(){
        return array(
		    'support' => '<a href="mailto:yakacj@yahoo.com?Subject=About%20your%20Price%20Changer%20Plugin" target="_blank">Support</a>',
		);
    }
    
    public function plugin_links( $links ) {
	    return array_merge( self::get_plugin_links(), $links );
    }
    
    public function plugin_row_meta( $links, $base ) {
		if ( YCPPC_BASE === $base ) {
			return array_merge( $links, self::get_meta_links() );
		}
		return (array) $links;
	}
	
    public static function instance() {
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Admin_Setup::instance();
