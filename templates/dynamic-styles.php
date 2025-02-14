<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit; ?>

.fcwc-cart .fcwc-cart-icon img{
    height: <?php echo esc_html( $style['fcwc_cart_icon_size']['height'] . 'px' ); ?>;
    width: <?php echo esc_html( $style['fcwc_cart_icon_size']['width'] . 'px' ); ?>;
}

.fcwc-cart .fcwc-cart-count {
    background-color: <?php echo esc_html( $style['fcwc_cart_icon_background_color']['color'] ); ?>;
    color: <?php echo esc_html( $style['fcwc_cart_icon_text_color']['color'] ); ?>;
}

.fcwc-cart .fcwc-cart-count:hover {
    background-color: <?php echo esc_html( $style['fcwc_cart_icon_background_color']['hover_color'] ); ?>;
    color: <?php echo esc_html( $style['fcwc_cart_icon_text_color']['hover_color'] ); ?>;
}

.fcwc-cart .fcwc-variable-add-to-cart-button,
.fcwc-cart .fcwc-external-product,
.fcwc-cart .fcwc-simple-add-cart-button {
    background-color: <?php echo esc_html( $style['fcwc_buy_now_button_background_color']['color'] ); ?>;
}

.fcwc-cart .fcwc-variable-add-to-cart-button label,
.fcwc-cart .fcwc-external-product,
.fcwc-cart .fcwc-simple-add-cart-button label,
.fcwc-cart .fcwc-variable-add-to-cart-button .fcwc-btn-loader,
.fcwc-cart .fcwc-external-product .fcwc-btn-loader,
.fcwc-cart .fcwc-simple-add-cart-button .fcwc-btn-loader {
    color: <?php echo esc_html( $style['fcwc_buy_now_button_text_color']['color'] ); ?>;
}

.fcwc-cart .fcwc-variable-add-to-cart-button:hover,
.fcwc-cart .fcwc-external-product:hover,
.fcwc-cart .fcwc-simple-add-cart-button:hover {
    background-color: <?php echo esc_html( $style['fcwc_buy_now_button_background_color']['hover_color'] ); ?>;
}

.fcwc-cart .fcwc-variable-add-to-cart-button:hover label,
.fcwc-cart .fcwc-external-product:hover,
.fcwc-cart .fcwc-simple-add-cart-button:hover label {
    color: <?php echo esc_html( $style['fcwc_buy_now_button_text_color']['hover_color'] ); ?>;
}


.fcwc-cart .fcwc-button-go-to-cart{
    background-color: <?php echo esc_html( $style['fcwc_shipping_button_background_color']['color'] ); ?>;
    color: <?php echo esc_html( $style['fcwc_shipping_button_text_color']['color'] ); ?>;
}

.fcwc-cart .fcwc-button-go-to-cart .fcwc-btn-loader{
    color: <?php echo esc_html( $style['fcwc_shipping_button_text_color']['color'] ); ?>;
}

.fcwc-cart .fcwc-button-go-to-cart:hover{
    background-color: <?php echo esc_html( $style['fcwc_shipping_button_background_color']['hover_color'] ); ?>;
    color: <?php echo esc_html( $style['fcwc_shipping_button_text_color']['hover_color'] ); ?>;
}

.fcwc-cart .fcwc-button-checkout-now{
    background-color: <?php echo esc_html( $style['fcwc_coupon_apply_button_background_color']['color'] ); ?>;
    color: <?php echo esc_html( $style['fcwc_checkout_button_text_color']['color'] ); ?>;
}

.fcwc-cart .fcwc-button-checkout-now .fcwc-btn-loader{
    color: <?php echo esc_html( $style['fcwc_checkout_button_text_color']['color'] ); ?>;
}

.fcwc-cart .fcwc-button-checkout-now:hover{
    background-color: <?php echo esc_html( $style['fcwc_coupon_apply_button_background_color']['hover_color'] ); ?>;
    color: <?php echo esc_html( $style['fcwc_checkout_button_text_color']['hover_color'] ); ?>;
}

.fcwc-cart .fcwc-apply-discount{
    background-color: <?php echo esc_html( $style['fcwc_coupon_apply_button_background_color']['color'] ); ?>;
}

.fcwc-cart .fcwc-apply-discount label,
.fcwc-cart .fcwc-apply-discount .fcwc-btn-loader {
    color: <?php echo esc_html( $style['fcwc_coupon_apply_button_text_color']['color'] ); ?>;
}

.fcwc-cart .fcwc-apply-discount:hover{
    background-color: <?php echo esc_html( $style['fcwc_coupon_apply_button_background_color']['hover_color'] ); ?>;
    color: <?php echo esc_html( $style['fcwc_coupon_apply_button_text_color']['hover_color'] ); ?>;
}

.fcwc-cart .fcwc-apply-discount:hover label {
    color: <?php echo esc_html( $style['fcwc_coupon_apply_button_text_color']['hover_color'] ); ?>;
}

#fcwc_place_order{
    background-color: <?php echo esc_html( $style['fcwc_place_order_button_background_color']['color'] ); ?>;
    color: <?php echo esc_html( $style['fcwc_place_order_button_text_color']['color'] ); ?>; 
}

#fcwc_place_order:hover{
    background-color: <?php echo esc_html( $style['fcwc_place_order_button_background_color']['hover_color'] ); ?>;
    color: <?php echo esc_html( $style['fcwc_place_order_button_text_color']['hover_color'] ); ?>;
}