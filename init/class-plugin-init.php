<?php 

/**
 * Plugin initializer class
 */
 
namespace YCPlugins\PriceChanger\Init;
use YCPlugins\PriceChanger\Admin\Notices\Plugin_Admin_Notices;

defined( 'ABSPATH' ) or exit;

class Plugin_Init{

    protected static $_instance = null;
    protected static $_prefix   = 'class-plugin-';
    
    public function __construct(){
        self::load_dependencies();
    }
    
    private static function load_dependencies(){
        $files = array( 
            'admin/' . self::$_prefix . 'admin-setup.php',
            'init/utils/' . self::$_prefix . 'utils.php',
            'price/' . self::$_prefix . 'price-filter.php',
            'init/astra/' . self::$_prefix . 'astra-notices.php',
            'admin/notices/' .self::$_prefix . 'admin-notices.php',
        );
		
		add_action( 'before_woocommerce_init', function() {
	        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YCPPC_FILE, true );
	        }
        } );
		
        foreach( $files as $file ){
            require_once plugin_dir_path( dirname( __FILE__ ) ) . $file;
        }

    }
	
    public static function instance() {
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Plugin_Init::instance();
