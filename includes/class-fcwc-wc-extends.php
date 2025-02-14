<?php
if ( ! class_exists( 'FCWC_WC_Extends' ) ) :

class FCWC_WC_Extends {

    public function __construct() {

        $fcwc_settings = get_option('fcwc_general');

        // Register the custom field type for WooCommerce settings.
        add_action( 'woocommerce_admin_field_color_combo_field', array( $this, 'render_color_combo_field' ), 10, 1 );
        add_filter( 'woocommerce_available_variation',  array( $this, 'variation_price_html' ), 10, 3 );

        add_filter('woocommerce_admin_field_height_width_field', array( $this, 'render_height_width_field' ), 10 );
        add_filter('woocommerce_admin_field_buy_pro_button', array( $this, 'render_buy_pro_button' ), 10 );
        add_shortcode( 'FCWC_Cart', array( $this, 'fullscreen_cart' ) );
    }

    /**
     * Fullscreen Cart Shortcode
     */
    public function fullscreen_cart( $attr ){
        if ( function_exists( 'WC') && is_object( WC()->cart ) ) :
            wp_enqueue_script('wc-checkout');
            wp_enqueue_script( 'wc-checkout' );
            wp_enqueue_script( 'wc-country-select' );
            wp_enqueue_script( 'wc-address-i18n' );
            $cart               = WC()->cart;
            $general_setting    = get_option( 'fcwc_general' );
            $product_setting    = get_option( 'fcwc_product' );
            $cart_settuing      = get_option( 'fcwc_cart' );
            $style_setting      = get_option( 'fcwc_style' );

            $cart_icon          = $general_setting['fcwc_cart_icon'];

            ob_start();
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
            return ob_get_clean();
        endif;
        return '';
    }

    /**
     * Render the custom colors field.
     */
    public function render_color_combo_field( $value ) { 
        $custom_attributes          = isset( $value['custom_attributes'] ) ? $value['custom_attributes'] : [];
        $custom_attributes          = array_map( 'esc_attr', $custom_attributes );
        $color_value                = isset( $value['value']['color'] ) ? esc_attr( $value['value']['color'] ) : '';
        $hover_color_value          = isset( $value['value']['hover_color'] ) ? esc_attr( $value['value']['hover_color'] ) : '';
        $option_value               = (array)WC_Admin_Settings::get_option( $value['id'] );
        $color                      = isset( $option_value['color'] ) && $option_value['color'] !== '' ? $option_value['color'] : $color_value;
        $hover_color                = isset( $option_value['hover_color'] ) && $option_value['hover_color'] !== '' ? $option_value['hover_color'] : $hover_color_value; ?>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr( $value['id'] ); ?>">
                    <?php echo esc_html( $value['title'] ); ?>
                </label>
            </th>
            <td 
                style="width: 160px"
                class="forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
                <span class="colorpickpreview" style="background: <?php echo esc_attr( $color ); ?>">&nbsp;</span>
                <input
                    name="<?php echo esc_attr( $value['field_name'] ); ?>[color]"
                    id="<?php echo esc_attr( $value['id'] ); ?>"
                    type="text"
                    dir="ltr"
                    style="width: 80px;<?php echo esc_attr( $value['css'] ); ?>"
                    value="<?php echo esc_attr( $color ); ?>"
                    class="<?php echo esc_attr( $value['class'] ); ?>colorpick"
                    placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                    <?php echo esc_attr( implode( ' ', $custom_attributes ) ); // WPCS: XSS ok. ?>
                /><p><small><b><?php esc_html_e( 'Normal Color', 'fullscreen-cart-for-woocommerce' ) ?><small><b></p>
                <div id="colorPickerDiv_<?php echo esc_attr( $value['id'] ); ?>" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
            </td>
            <td 
                class="forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
                <span class="colorpickpreview" style="background: <?php echo esc_attr( $hover_color ); ?>">&nbsp;</span>
                <input
                    name="<?php echo esc_attr( $value['field_name'] ); ?>[hover_color]"
                    id="<?php echo esc_attr( $value['id'] ); ?>"
                    type="text"
                    dir="ltr"
                    style="width: 80px;<?php echo esc_attr( $value['css'] ); ?>"
                    value="<?php echo esc_attr( $hover_color ); ?>"
                    class="<?php echo esc_attr( $value['class'] ); ?>colorpick"
                    placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                    <?php echo esc_attr( implode( ' ', $custom_attributes )); // WPCS: XSS ok. ?>
                /><p><small><b><?php esc_html_e( 'Hover Color', 'fullscreen-cart-for-woocommerce' ) ?><small><b></p>
                <div id="colorPickerDiv_<?php echo esc_attr( $value['id'] ); ?>" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
            </td>
        </tr>
        <?php
    }

    /**
     * Height Width WooCommerec Field
     */
    public function render_height_width_field( $value ) {
        // Retrieve saved options or defaults
        $option_value   = WC_Admin_Settings::get_option($value['id'], $value['default']);
        $height         = isset($option_value['height']) ? $option_value['height'] : '';
        $width          = isset($option_value['width']) ? $option_value['width'] : ''; ?>

        <tr class="<?php echo esc_attr( $value['row_class'] ); ?>">
            <th scope="row" class="titledesc">
            <label><?php echo esc_html( $value['title'] ); ?></label>
        </th>
            <td class="forminp image_width_settings" style="width: 160px;display: table-cell;">
                <input name="<?php echo esc_attr( $value['field_name'] ); ?>[width]" id="<?php echo esc_attr( $value['id'] ); ?>-width" type="text" size="3" value="<?php echo esc_attr( $width ); ?>" /> &times; <input name="<?php echo esc_attr( $value['id'] ); ?>[height]" id="<?php echo esc_attr( $value['id'] ); ?>-height" type="text" size="3" value="<?php echo esc_attr( $height ); ?>" />
            </td>
        </tr>
        <?php
    }

    /**
     * Add Price html field in woocomemrce variation 
     */
    public function variation_price_html( $variation_data, $product, $variation ) {
        $variation_data['fcwc_price_html'] = $variation->get_price_html();
        return $variation_data;
    }

    /**
     * Add Buy Now Pro button field 
     */
    public function render_buy_pro_button( $value ) {  ?>
        <tr class="<?php echo esc_attr( $value['row_class'] ); ?>">
            <th scope="row" class="titledesc">
                <label><?php echo esc_html( $value['title'] ); ?></label>
            </th>
            <td class="forminp">
                <a href="<?php echo esc_url( $value['pro_link'] ); ?>" target="_blank" class="fcwc-pro-btn">
                    <span class="shine-content"><?php echo esc_html( $value['button_text'] ); ?></span>
                </a>
                <p class="description">
                    <?php echo esc_html( $value['description'] ); ?>
                </p>
            </td>
        </tr>
        <?php
    }
    
}

new FCWC_WC_Extends();

endif;
