<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="fcwc-offers-wrap fcwc-simple-scroll">
    <div class="fcwc-offers fcwc-offers-simple">
        <ul class="fcwc-offers-list">
            <?php foreach( $products as $product_id ) : 
                $product = wc_get_product( $product_id ); ?>
                <li class="fcwc-offer-item-wrap">
                    <?php if( isset( $settings['fcwc_show_product_image'] ) && 'yes' === $settings['fcwc_show_product_image'] ) : ?>                        
                        <div class="fcwc-offer-item-icon">
                            <?php echo wp_kses_post( $product->get_image( [80, 80 ] ) ); ?>
                        </div>
                    <?php endif; ?>
       
                    <div class="fcwc-offer-item-name">
                        <a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
                    </div>

                    <?php if( isset( $settings['fcwc_show_product_description'] ) && 'yes' === $settings['fcwc_show_product_description'] ) : ?>                        
                        <div class="fcwc-offer-item-content">
                            <div class="fcwc-offer-item-content-icon"></div>
                            <div class="fcwc-offer-item-content-description" style="display: none;">
                                <?php echo wp_kses_post( $product->get_short_description() ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if( isset( $settings['fcwc_show_product_price'] ) && 'yes' === $settings['fcwc_show_product_price'] ) : ?> 
                        <div class="fcwc-offer-item-price">
                            <?php echo wp_kses_post( $product->get_price_html() ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="fcwc-offer-item-link">
                        <div class="fcwc-purchase-submit-wrapper">
                            <?php if( $product->get_type() == 'external' ) :
                                $product_url = get_post_meta( $product->get_id(), '_product_url', true ); ?>
                                <a class="fcwc-external-product fcwc-button" target="_blank" href="<?php echo esc_url( $product_url ); ?>">
                                    <?php echo esc_html( $settings['fcwc_buy_button_text'] ); ?>
                                </a>
                            <?php else : ?>
                                <button 
                                    class="fcwc-simple-add-cart-button fcwc-button" 
                                    data-id="<?php echo intval( $product->get_id() ); ?>"
                                    data-type="<?php echo esc_attr( $product->get_type() ); ?>" 
                                >
                                    <label><?php echo esc_html( $settings['fcwc_buy_button_text'] ); ?></label>
                                    <span class="fcwc-btn-loader">
                                        <span>&bull;</span>
                                        <span>&bull;</span>
                                        <span>&bull;</span>
                                    </span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
