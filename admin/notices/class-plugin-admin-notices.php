<?php
/**
 * Price Changer Admin Notices.
 *
 * @package PriceChanger
 */

namespace YCPlugins\PriceChanger\Admin\Notices;
use YCPlugins\PriceChanger\Init\Astra\Astra_Notices;

defined( 'ABSPATH' ) or exit;

/**
 * Class Plugin_Admin_Notice.
 */
class Plugin_Admin_Notices {

	/**
	 * Instance
	 *
	 * @access private
	 * @var object Class object.
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Class Initiator
	 *
	 * @since 1.0.0
	 * @return  object  class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		\add_action( 'admin_init', array( $this, 'render_plugin_notices' ) );
	}

	/**
	 *  Show admin notices.
	 */
	public function render_plugin_notices() {
		Astra_Notices::add_notice(
			array(
				'id'                   => 'price-changer-5-star-rev-notice',
				'type'                 => 'info',
				'class'                => 'price-changer-5-star',
				'show_if'              => self::needto_show_notice(),
				'message'              => self::get_message(), 
				'repeat-notice-after'  => 3 * WEEK_IN_SECONDS,
				'display-notice-after' => 2 * WEEK_IN_SECONDS ,
			)
		);
	}

	/**
	 * Check for page to show on
	 *
	 * @return bool
	 */
	private static function needto_show_notice() {
	    if( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS ) == 'price-changer' ) return true;
	}
	
	/**
	 * Get notice message
	 */
	private static function get_message(){
	    $image_url  = esc_url( \apply_filters( 'price_changer_logo_url','http://plugins.svn.wordpress.org/price-changer-for-woocommerce/assets/icon-256x256.png' ) );
		$review_url = esc_url( \apply_filters( 'price_changer_review_url', 'https://wordpress.org/support/plugin/price-changer-for-woocommerce/reviews/?filter=5#new-post' ) );
		
		/* translators: %1$s Logo url, %2$s notice heading, %3$s notice details, %4$s already did,
		%5$s next timer, %6$s maybe later, %7$s review url, %8$s review button*/
	    return 	sprintf( 
	        '<div class="notice-image" style="display: flex;">
                <img src="%1$s" class="custom-logo" alt="PriceChangerLogo" itemprop="logo" style="max-width: 50px;"></div>
                <div class="notice-content">
                    <div class="notice-heading" style="font-weight:bold;">
                        %2$s
                    </div>
                    %3$s
                    <div class="astra-review-notice-container">
                        <span class="dashicons dashicons-smiley"></span>
                        <a href="#" class="astra-notice-close astra-review-notice">%4$s</a>
                        <span class="dashicons dashicons-calendar"></span>
                        <a href="#" data-repeat-notice-after="%5$s" class="astra-notice-close astra-review-notice">%6$s</a>
                        <a href="%7$s" class="astra-notice-close astra-review-notice button-primary" target="_blank" style="margin-left: 15px;"><span class="dashicons dashicons-thumbs-up" style="vertical-align: middle;padding: 3px;"></span>%8$s</a>
                    </div>
                </div>',
				$image_url,
				__( ' Thank you for using "Price Changer"', 'price-changer-for-woocommerce' ),
				/* translators: %s star symbols */
				sprintf( __( 'Could you please do a BIG favor and give 5 %s ratings to our plugin on WordPress? <br>This will help others and inspired us to develop such a cool plugins in future also.', 'price-changer-for-woocommerce' ),'★★★★★' ),
				__( 'already did', 'price-changer-for-woocommerce' ),
				3 * WEEK_IN_SECONDS,
				__( 'Maybe later', 'price-changer-for-woocommerce' ),
				$review_url,
				__( 'Okay', 'price-changer-for-woocommerce' )
		);
	}
}

Plugin_Admin_Notices::get_instance();
