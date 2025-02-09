<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

class FCWC_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'FCWC_woocommerce_cart_popup';
    }

    public function get_title() {
        return __( 'Fullscreen Cart', 'fullscreen-cart-for-woocommerce' );
    }

    public function get_icon() {
        return 'eicon-cart';
    }

    public function get_categories() {
        return [ 'woocommerce-elements' ];
    }

    protected function render() {
        
        if ( function_exists( 'WC') && is_object( WC()->cart ) ) :
            $cart               = WC()->cart;
            $general_setting    = get_option( 'fcwc_general' );
            $product_setting    = get_option( 'fcwc_product' );
            $cart_settuing      = get_option( 'fcwc_cart' );
            $style_setting      = get_option( 'fcwc_style' );

            $cart_icon          = $general_setting['fcwc_cart_icon'];
            
            wc_get_template(
                'fullscreen-cart.php', // The template file name
                array(
                    'settings'          => (array)$general_setting + (array)$product_setting + (array)$cart_settuing + (array)$style_setting,
                    'cart'              => $cart,
                    'products'          => fcwc_get_products( $product_setting ),
                    'cart_icon'         => $cart_icon
                ),
                'fullscreen-cart-for-woocommerce/',
                FCWC_TEMPLATE_PATH
            );

        endif;
    }
}