<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'fcwc_get_default_options',
    array(
        'fcwc_style' => array(
            'fcwc_cart_icon_size'                       => array( 
                'width'         => 35,
                'height'        => 35
            ),
            'fcwc_cart_icon_text_color'                 => array( 
                'color'         => '#ffffff',
                'hover_color'   => '#ffffff'
            ),
            'fcwc_cart_icon_background_color'           => array( 
                'color'         => '#7f54b3',
                'hover_color'   => '#7b43c0'
            ),
            'fcwc_buy_now_button_text_color'            => array( 
                'color'         => '#ffffff',
                'hover_color'   => '#ffffff'
            ),
            'fcwc_buy_now_button_background_color'      => array( 
                'color'         => '#7f54b3',
                'hover_color'   => '#7b43c0'
            ),
            'fcwc_shipping_button_text_color'           => array( 
                'color'         => '#ffffff',
                'hover_color'   => '#ffffff'
            ),
            'fcwc_shipping_button_background_color'     => array( 
                'color'         => '#7f54b3',
                'hover_color'   => '#7b43c0'
            ),
            'fcwc_checkout_button_text_color'           => array( 
                'color'         => '#ffffff',
                'hover_color'   => '#ffffff'
            ),
            'fcwc_checkout_button_background_color'     => array( 
                'color'         => '#7f54b3',
                'hover_color'   => '#7b43c0'
            ),
            'fcwc_coupon_apply_button_text_color'       => array( 
                'color'         => '#ffffff',
                'hover_color'   => '#ffffff'
            ),
            'fcwc_coupon_apply_button_background_color' => array( 
                'color'         => '#7f54b3',
                'hover_color'   => '#7b43c0
            ')
        ),
        'fcwc_cart' => array(
            'fcwc_shopping_cart_section_title'       => esc_html__( 'Shopping Cart', 'fullscreen-cart-for-woocommerce' ),
            'fcwc_show_coupon_apply_form'            => 'yes',
            'fcwc_empty_cart_message'                => esc_html__( 'Your cart is empty.', 'fullscreen-cart-for-woocommerce' ),
            'fcwc_show_go_to_cart_button'            => 'yes',
            'fcwc_go_to_cart_button_text'            => esc_html__( 'Shopping Cart', 'fullscreen-cart-for-woocommerce' ),
            'fcwc_show_go_to_checkout_button'        => 'yes',
            'fcwc_go_to_checkout_button_text'        => esc_html__( 'Checkout Now', 'fullscreen-cart-for-woocommerce' ),
        ),
        'fcwc_product' => array(
            'fcwc_product_section_title'             => esc_html__( 'Additional Options', 'fullscreen-cart-for-woocommerce' ),
            'fcwc_product_category'                  => array(),
            'fcwc_number_of_products'                => 10,
            'fcwc_show_product_image'                => 'yes',
            'fcwc_show_product_description'          => 'yes',
            'fcwc_show_product_price'                => 'yes',
            'fcwc_buy_button_text'                   => esc_html__( 'Buy Now', 'fullscreen-cart-for-woocommerce' ),
        ),
        'fcwc_general' => array(
            'fcwc_cart_icon'                         => FCWC_URL . 'assets/img/cart-icon.svg',
            'fcwc_show_cart_total'                   => 'yes'
        )
    )
);
