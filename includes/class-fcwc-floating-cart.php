<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'FCWC_Floating_Cart' ) ) :

    class FCWC_Floating_Cart {

        public function __construct() {

            $fcwc_settings = get_option('fcwc_general');
        
            if ( isset($fcwc_settings['fcwc_show_floating_cart']) && 'yes' === $fcwc_settings['fcwc_show_floating_cart'] ) :
                add_action( 'wp_footer', array( $this, 'fcwc_floating_cart' ) );
            endif;
        
        }

        /**
         *     Floating Cart
         */
        public function fcwc_floating_cart( $attr ){
            if ( function_exists( 'WC') && is_object( WC()->cart ) ) :
                $cart               = WC()->cart;
                $general_setting    = get_option( 'fcwc_general' );
                $product_setting    = get_option( 'fcwc_product' );
                $cart_settuing      = get_option( 'fcwc_cart' );
                $style_setting      = get_option( 'fcwc_style' );

                $cart_icon          = $general_setting['fcwc_cart_icon'];
                $floating_position  = ! empty( $general_setting['fcwc_floating_cart_position'] ) ? esc_attr( $general_setting['fcwc_floating_cart_position'] ) : 'bottom_right';

                echo '<div class="fcwc-floating-cart ' . esc_attr( $floating_position ) . '">';
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
                echo '</div>';
            endif;
            return '';
        }
        
    }

new FCWC_Floating_Cart();

endif;
