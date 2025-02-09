<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'FCWC_Cart_Controller' ) ) :

    /**
     * Main FCWC_Cart_Controller Class
     *
     * @class FCWC_Cart_Controller
     * @version 1.0.0
     */
    class FCWC_Cart_Controller {

        public $settings = [];
        
        /**
         * Constructor for the class.
         */
        public function __construct() {
            $general_setting    = get_option( 'fcwc_general' );
            $product_setting    = get_option( 'fcwc_product' );
            $cart_settuing      = get_option( 'fcwc_cart' );
            $style_setting      = get_option( 'fcwc_style' );

            $this->settings     = (array)$general_setting + (array)$product_setting + (array)$cart_settuing + (array)$style_setting;
            $this->event_handler();
        }

        /**
         * Initialize hooks and filters.
         */
        private function event_handler() {
            add_action('wp_ajax_update_cart_qty', array( $this, 'update_cart_qty_handler' ) );
            add_action('wp_ajax_nopriv_update_cart_qty', array( $this, 'update_cart_qty_handler' ) );

            add_filter('woocommerce_add_to_cart_fragments', array( $this, 'cart_content_fragment' ), 20, 1 );    
        }

        public function update_cart_qty_handler() {
            // nonce verifications
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'update_cart_qty_nonce' ) ) :
                wp_send_json_error( [ 'message' => __( 'Invalid nonce verification.', 'fullscreen-cart-for-woocommerce' ) ] );
            endif;

            if ( ! isset( $_POST['cart_item_key'] ) || ! isset( $_POST['quantity'] ) ) :
                wp_send_json_error( [ 'message' => __( 'Missing required fields.', 'fullscreen-cart-for-woocommerce' ) ] );
            endif;

            $cart_item_key = sanitize_text_field(wp_unslash($_POST['cart_item_key']));
            $quantity = (int) sanitize_text_field(wp_unslash($_POST['quantity']));
        
            if (WC()->cart->get_cart_item($cart_item_key)) :
                WC()->cart->set_quantity($cart_item_key, $quantity, true); // Update quantity
                wc_add_to_cart_message([], true); // Optional: Add a message
                wp_send_json_success([
                    'fragments' => WC_AJAX::get_refreshed_fragments(),
                ]);
            else :
                wp_send_json_error(['message' => __('Invalid cart item key.', 'fullscreen-cart-for-woocommerce')]);
            endif;
        }

        public function cart_content_fragment( $fragments){
            $cart = WC()->cart;
            ob_start();
            wc_get_template(
                'content-cart.php', // The template file name
                array(
                    'settings'  => $this->settings,
                    'cart'      => $cart,
                ),
                'fullscreen-cart-for-woocommerce/',
                FCWC_TEMPLATE_PATH
            ); 
            $fragments['.fcwc-shopping-cart'] = ob_get_clean();
            $fragments['.fcwc-cart-count']    = apply_filters( 'fcwc_cart_content_count', '<span class="fcwc-cart-count">' . esc_html( $cart->get_cart_contents_count() ) . '</span>' );
            return $fragments;
        }
        
    }

    new FCWC_Cart_Controller();
endif;