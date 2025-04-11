<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="fcwc-shopping-cart fcwc-cart-wrap fcwc-simple-scroll">
    <div class="fcwc-cart-inner fcwc-cart-default">
        <div class="fcwc-cart-data-widget">
            <ul class="fcwc-cart-list">
                <?php if( ! $cart->is_empty() ) : 
                    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) :
                        $product            = $cart_item['data'];
                        $product_id         = $cart_item['product_id'];
                        $product_permalink  = $product->is_visible() ? $product->get_permalink( $cart_item ) : '';
                        $product_title      = $product->get_name();
                        $quantity           = $cart_item['quantity'];
                        $product_price      = $cart->get_product_price( $product ); ?>

                        <li class="fcwc-cart-item type-offer" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                            <div class="fcwc-cart-item-icon">
                                <?php echo wp_kses_post( $product->get_image( [ 80, 80 ] ) ); ?>
                            </div>
                            <div class="fcwc-cart-item-title">
                                <?php if ( $product_permalink ) : ?>
                                    <a href="<?php echo esc_url( $product_permalink ); ?>"><span><?php echo esc_html( $product_title ); ?></span></a>
                                <?php else : ?>
                                    <span><?php echo esc_html( $product_title ); ?></span>
                                    <?php // Meta data.
						            echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.?>
                                <?php endif; ?>
                                <span class="fcwc-cart-item-quantity"><?php echo esc_html( sprintf( _n( '%s Item', '%s Items', $quantity, 'fullscreen-cart-for-woocommerce' ), $quantity ) ); ?>
                                </span>
                            </div>
                            <div class="fcwc-cart-item-space"></div>
                            <div class="fcwc-cart-item-qty">
                                <input type="number" name="<?php echo esc_attr( 'cart[' . $cart_item_key . '][qty]' ) ?>" step="1" min="1" value="<?php echo esc_attr( $quantity ) ?>" />
                            </div>
                            <div class="fcwc-cart-item-price">
                                <?php echo wp_kses_data( $product_price ); ?>
                            </div>
                            <a data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" class="fcwc-remove-from-cart">
                                <label>&#10005;</label>
                                <span class="fcwc-btn-loader">
                                    <span>&bull;</span>
                                    <span>&bull;</span>
                                    <span>&bull;</span>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <li class="fcwc-cart-item fcwc-cart-meta fcwc-subtotal">
                        <span><?php esc_html_e( 'Subtotal:', 'fullscreen-cart-for-woocommerce' ); ?></span>
                        <span class="cart-subtotal"><?php echo $cart->get_cart_subtotal(); ?></span> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </li>

                    <?php if ( $cart->has_discount() ) :
                        // Get the applied coupon codes
                        $applied_coupons = $cart->get_coupons();
                        foreach ( $applied_coupons as $coupon_code => $coupon ) :
                            $amount               = $cart->get_coupon_discount_amount( $coupon->get_code(), $cart->display_cart_ex_tax );
                            $discount_amount_html = '-' . wc_price( $amount ); ?>
                            
                            <li class="fcwc-coupon-item fcwc-cart-discount-row">
                                <span>
                                    <b><?php esc_html_e( 'Coupon: ', 'fullscreen-cart-for-woocommerce' ); ?><u><?php echo esc_html( $coupon->get_code() ); ?></u></b>
                                </span>
                                <span class="fcwc-flex coupon-code">
                                    <b><?php echo wp_kses_post( $discount_amount_html ); ?></b>
                                    <a class="fcwc-remove-coupon" data-coupon="<?php echo esc_attr( $coupon_code ); ?>">
                                        <label><?php esc_html_e( '[Remove]', 'fullscreen-cart-for-woocommerce' ); ?></label>
                                        <span class="fcwc-btn-loader">
                                            <span>&bull;</span>
                                            <span>&bull;</span>
                                            <span>&bull;</span>
                                        </span>
                                    </a>
                                </span>
                            </li>
                        <?php endforeach;
                    endif; ?>
                    
                    <?php if( $cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                        <li class="fcwc-cart-item fcwc-cart-meta fcwc-shipping">
                            <span><b><?php esc_html_e( 'Shipping:', 'fullscreen-cart-for-woocommerce' ); ?></b></span>
                            <span class="cart-shipping"><b><?php echo wp_kses_post(wc_price($cart->get_shipping_total() )); ?></b>
                                <?php do_action( 'fcwc_shooping_cart_shipping', $cart, $settings ); ?>
                            </span>
                        </li>
                    <?php endif; ?>
                        

                    <?php foreach ( $cart->get_fees() as $fee ) : ?>
                        <li class="fcwc-cart-item fcwc-fee">
                            <span><?php echo esc_html( $fee->name ); ?></span>
                            <span class="cart-total" data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></span>
                        </li>
                    <?php endforeach; ?>

                    <?php if ( wc_tax_enabled() && !$cart->display_prices_including_tax() ) :
                        $taxable_address = WC()->customer->get_taxable_address();
                        $estimated_text  = '';

                        if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) :
                            /* translators: %s location. */
                            $estimated_text = sprintf( 
                                ' <small>' . esc_html__( '(estimated for %s)', 'fullscreen-cart-for-woocommerce' ) . '</small>', 
                                esc_html( WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
                            );
                        endif;

                        if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) :
                            foreach ($cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                ?>
                                <li class="fcwc-cart-item tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                                    <span><b><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b></span>
                                    <span data-title="<?php echo esc_attr( $tax->label ); ?>"><b><?php echo wp_kses_post( $tax->formatted_amount ); ?></b></span>
                                </li>
                            <?php endforeach;
                        else : ?>
                            <li class="tax-total">
                                <span><b><?php echo esc_html( $cart->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b></span>
                                <span data-title="<?php echo esc_attr( $cart->tax_or_vat() ); ?>"><b><?php wc_cart_totals_taxes_total_html(); ?></b></span>
                            </li>
                            <?php
                        endif;
                    endif; ?>

                    <li class="fcwc-cart-item fcwc-cart-meta fcwc-total">
                        <span><?php esc_html_e( 'Total:', 'fullscreen-cart-for-woocommerce' ); ?></span><span class="cart-total"><?php wc_cart_totals_order_total_html(); ?></span>
                    </li>

                    <?php if( isset( $settings['fcwc_show_coupon_apply_form'] ) && 'yes' === $settings['fcwc_show_coupon_apply_form'] ) : ?>
                        <li class="fcwc-cart-item fcwc-promocode">
                            <fieldset id="fcwc-discount-code"> 
                                <div id="fcwc-show-discount" >
                                    <?php esc_html_e( 'Have a discount code?', 'fullscreen-cart-for-woocommerce' ); ?>
                                    <a href="javascript:void(0)" class="fcwc-discount-link">
                                        <?php esc_html_e( 'Entering a discount code', 'fullscreen-cart-for-woocommerce' ); ?>
                                    </a>
                                </div>
                                <div id="fcwc-discount-code-wrap" class="fcwc-cart-adjustment" style="display:none;">
                                    <label class="fcwc-label" for="fcwc-discount">
                                        <span class="fcwc-description"><?php esc_html_e( 'Enter a coupon code if you have one.', 'fullscreen-cart-for-woocommerce' ); ?></span> 
                                        <span class="fcwc-discount-code-field-wrap">
                                            <input type="text" id="fcwc-discount" class="fcwc-discount fcwc-input" name="fcwc-discount" placeholder="Enter discount" value="">
                                            <button type="submit" class="fcwc-apply-discount fcwc-button">
                                                <label><?php esc_html_e( 'Apply', 'fullscreen-cart-for-woocommerce' ); ?></label>
                                                <span class="fcwc-btn-loader">
                                                    <span>&bull;</span>
                                                    <span>&bull;</span>
                                                    <span>&bull;</span>
                                                </span>
                                            </button>
                                        </span>
                                        <span id="fcwc-discount-error-wrap" class="fcwc-error fcwc-alert fcwc-alert-error" aria-hidden="true" style="display:none;"></span>
                                    </label>
                                </div>
                            </fieldset> 	
                        </li>
                    <?php endif; ?>

                    <li class="fcwc-cart-item fcwc-checkout">
                        <?php if( isset( $settings['fcwc_show_go_to_cart_button'] ) && 'yes' === $settings['fcwc_show_go_to_cart_button'] ) : ?>
                            <a class="fcwc-button fcwc-button-go-to-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" >
                                <?php echo esc_html( $settings['fcwc_go_to_cart_button_text'] ); ?>
                            </a>
                        <?php endif; ?>

                        <?php if( isset( $settings['fcwc_show_go_to_checkout_button'] ) && 'yes' === $settings['fcwc_show_go_to_checkout_button'] ) : ?>
                            <a class="fcwc-button fcwc-button-checkout-now <?php echo esc_attr(apply_filters( 'fcwc_checkout_button_classes', '', $settings )); ?>" href="<?php echo esc_url( wc_get_checkout_url() ); ?>" >
                                <?php echo esc_html( $settings['fcwc_go_to_checkout_button_text'] ); ?>
                            </a>
                        <?php endif; ?>
                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                    </li>
                    <li class="fcwc-payment-option">
                        <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
                    </li>
                    
                    <li style="display:none" class="fcwc-notice-message"></li>
                <?php else : ?>
                    <li class="fcwc-cart-item empty"><span class="fcwc-empty-cart"><?php echo esc_html( $settings['fcwc_empty_cart_message'] ); ?></span></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>