<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="fcwc-fullscreen-cart" class="fcwc-cart" >
    <?php if ( isset( $cart_icon ) && ! empty( $cart_icon ) ) :?>
        <div class="fcwc-cart-icon">
            <img src="<?php echo esc_url( $cart_icon ); ?>" alt="fullscreen-cart-for-woocommerce" />
        </div>
    <?php endif; 
    if( isset( $settings['fcwc_show_cart_total'] ) && 'yes' === $settings['fcwc_show_cart_total'] ) : ?> 
        <span class="fcwc-cart-count"><?php echo esc_html( $cart->get_cart_contents_count() ); ?></span>
    <?php endif; ?>

    <div class="fcwc-cart-widget fcwc-widget-area scheme-default" style="display:none">
        <span class="fcwc-cart-widget-close fcwc-close">&#10005;</span>
        <div class="fcwc-cart-parts">
            <div class="fcwc-cart-parts-offers">
                <h3><?php echo wp_kses_post( $settings['fcwc_product_section_title'] ); ?></h3>
                <?php 
                    wc_get_template(
                        'content-products.php', // The template file name
                        array(
                            'settings' => $settings,
                            'products' => $products,
                        ),
                        'fullscreen-cart-for-woocommerce/',
                        FCWC_TEMPLATE_PATH
                    ); 
                ?>
            </div>
            <div class="fcwc-cart-parts-shopping">
                <h3><?php echo wp_kses_post( $settings['fcwc_shopping_cart_section_title'] ); ?></h3>
                <?php 
                    wc_get_template(
                        'content-cart.php', // The template file name
                        array(
                            'settings'  => $settings,
                            'cart'      => $cart,
                        ),
                        'fullscreen-cart-for-woocommerce/',
                        FCWC_TEMPLATE_PATH
                    ); 
                ?>
            </div>
        </div>
        <?php do_action( 'fcwc_after_popup_html', $cart, $settings ); ?>
    </div>
    <?php do_action( 'fcwc_checkout_html' ) ?>
</div>
