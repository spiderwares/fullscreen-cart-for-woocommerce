<?php 

// Create a new settings page class
if ( ! class_exists( 'FCWC_Settings_Fullscreen_Cart' ) ) :

    class FCWC_Settings_Fullscreen_Cart {

        /**
         * The ID of the settings tab.
         *
         * @var string
         */
        private $id;

        /**
         * The label of the settings tab.
         *
         * @var string
         */
        private $label;

        /**
         * Constructor
         */
        public function __construct() {
            $this->id                 = 'fcwc_fullscreen_cart';
            $this->label              = __('Fullscreen Cart', 'fullscreen-cart-for-woocommerce');

            $this->event_handler(); // Call the event handler method.
        }

        public function event_handler() {
            add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 25, 1);
            add_action( 'woocommerce_settings_' . $this->id, array( $this, 'settings_tab' ) );
            add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save_settings' ) );
            add_action( 'woocommerce_sections_' . $this->id, array( $this, 'get_sections' ) );
        }

        /**
         * Add settings tab
         *
         * @param  array $settings_tabs Settings tabs array.
         * @return array
         */
        public function add_settings_tab( $settings_tabs ) {
            $settings_tabs[ $this->id ] = $this->label; // Add the settings tab to the tabs array.
            return $settings_tabs; // Return the modified settings tabs array.
        }

        /**
         * Settings tab content
         */
        public function settings_tab() {
            woocommerce_admin_fields( $this->get_settings() ); // Display the settings fields.
        }

        /**
         * Save woocommerce settings
         */
        public function save_settings(){
            woocommerce_update_options( $this->get_settings() ); // Display the settings fields.
        }

        /**
         * Generate the settings tab sections.
         */
        public function get_sections() {
            global $current_section;
            // Define the sections.
            $sections = array(
                ''          => __( 'Cart Settings', 'fullscreen-cart-for-woocommerce' ),
                'products'  => __( 'Products Settings', 'fullscreen-cart-for-woocommerce' ),
                'checkout'  => __( 'Shopping Cart Settings', 'fullscreen-cart-for-woocommerce' ),
                'style'     => __( 'Style Settings', 'fullscreen-cart-for-woocommerce' ),
            ); ?>
            <ul class="subsubsub">
                <?php foreach ( $sections as $id => $label ) : ?>
                    <?php
                    $url = add_query_arg(
                        array(
                            'page'    => 'wc-settings',
                            'tab'     => $this->id,
                            'section' => $id,
                        ),
                        admin_url( 'admin.php' )
                    );
                    $current        = isset( $current_section ) && $current_section === $id ? 'class=current' : '';
                    $section_keys   = array_keys( $sections );
                    $separator      = ( end( $section_keys ) === $id ) ? '' : '|'; ?>
                    <li>
                        <a href="<?php echo esc_url( $url ); ?>" <?php echo esc_attr( $current ); ?>><?php echo esc_html( $label ); ?></a> <?php echo esc_html( $separator ); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <br class="clear" />
            <?php
        }

        public function get_settings() {
            global $current_section;
            switch ( $current_section ) {
                case 'products':
                    $settings = $this->get_product_settings();
                    break;
                case 'checkout':
                    $settings = $this->get_shopping_cart_settings();
                    break;
                case 'style':
                    $settings = $this->get_style_settings();
                    break;
                default:
                    $settings = $this->get_cart_settings();
                    break;
            }
            return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section );
        }

        private function get_cart_settings() {
            return array(
                'title' => array(
                    'title' => __( 'Cart Settings', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'fcwc_cart_settings',
                ),
                'cart_icon' => array(
                    'title'   => __( 'Cart Icon', 'fullscreen-cart-for-woocommerce' ),
                    'type'    => 'url',
                    'desc'    => __( 'Upload a  cart icon image URL.', 'fullscreen-cart-for-woocommerce' ),
                    'id'      => 'fcwc_general[fcwc_cart_icon]',
                    'default' => FCWC_URL . 'assets/img/cart-icon.svg'
                ),
                'show_cart_total' => array(
                    'title'   => __( 'Show Cart Item Total', 'fullscreen-cart-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Show the total number of items in the cart.', 'fullscreen-cart-for-woocommerce' ),
                    'id'      => 'fcwc_general[fcwc_show_cart_total]',
                    'default' => 'yes',
                ),
                'show_floating_cart' => array(
                    'title'   => __( 'Enable Floating Cart Icon', 'fullscreen-cart-for-woocommerce' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Show a floating cart icon on the screen.', 'fullscreen-cart-for-woocommerce' ),
                    'id'      => 'fcwc_general[fcwc_show_floating_cart]',
                    'default' => 'yes',
                ),
                'floating_cart_position' => array(
                    'title'   => __( 'Floating Cart Position', 'fullscreen-cart-for-woocommerce' ),
                    'type'    => 'select',
                    'desc'    => __( 'Choose the position for the floating cart icon.', 'fullscreen-cart-for-woocommerce' ),
                    'id'      => 'fcwc_general[fcwc_floating_cart_position]',
                    'default' => 'bottom_right',
                    'options' => array(
                        'bottom_right'  => __( 'Bottom Right', 'fullscreen-cart-for-woocommerce' ),
                        'bottom_left'   => __( 'Bottom Left', 'fullscreen-cart-for-woocommerce' ),
                        'top_right'     => __( 'Top Right', 'fullscreen-cart-for-woocommerce' ),
                        'top_left'      => __( 'Top Left', 'fullscreen-cart-for-woocommerce' ),
                    ),
                ),
                'section_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_cart_settings',
                ),
                'shortcode_title'  => array(
                    'title' => __( 'Shortcode', 'fullscreen-cart-for-woocommerce' ),
                    'desc'  => __( 'You can use the <code>[FCWC_Cart]</code> shortcode to display the full-screen cart with the cart icon. Simply insert this shortcode into your page or post to showcase the full-screen cart functionality seamlessly.', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'id'    => 'fcwc_cart_shortcode_settings',
                ),
                'shortcode_section_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_cart_shortcode_settings',
                ),
                'override_tempalte_title'  => array(
                    'title' => __( 'Overriding Templates', 'fullscreen-cart-for-woocommerce' ),
                    'desc'  => __( 'In the Fullscreen Cart for WooCommerce plugin, templates are located in the templates directory. For example, the content-cart.php file is located at: <code>your-child-theme/fullscreen-cart-for-woocommerce/content-cart.php</code>', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'id'    => 'fcwc_cart_override_tempalte_settings',
                ),
                'override_tempalte_section_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_cart_override_tempalte_settings',
                ),
            );
        }

        private function get_product_settings() {
            $settings = array(
                'title' => array(
                    'title' => __('Products Settings', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'fcwc_products_settings'
                ),
                'product_section_title' => array(
                    'title' => __('Product Section Title', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'text',
                    'id' => 'fcwc_product[fcwc_product_section_title]',
                    'default' => __('Additional Options', 'fullscreen-cart-for-woocommerce')
                ),
                'product_category' => array(
                    'title'     => __('Product Categories', 'fullscreen-cart-for-woocommerce'),
                    'type'      => 'multiselect',
                    'desc'      => __('Select the product categories to display.', 'fullscreen-cart-for-woocommerce'),
                    'id'        => 'fcwc_product[fcwc_product_category]',
                    'class'     => "wc-enhanced-select",
                    'options'   => $this->get_product_categories()
                ),
                'excluded_product' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_excluded_product',
                    'title'       => esc_html__( 'Exclude Product', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION,
                    'description' => esc_html__( 'Upgrade to the Pro version to Exclude Product From the full screen cart.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'includes_product' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_includes_product',
                    'title'       => esc_html__( 'Include Product Only', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION,
                    'description' => esc_html__( 'Upgrade to the Pro version to set only Selected Product on the full screen cart.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'product_type' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_product_type',
                    'title'       => esc_html__( 'Select Product Types', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION,
                    'description' => esc_html__( 'Upgrade to the Pro version to enable support for additional product types, such as variable and grouped products. By default, only simple products are supported.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'product_is' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'select_prodyct_types',
                    'title'       => esc_html__( 'Product is', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION,
                    'description' => esc_html__( 'Upgrade to the Pro version to enable support for displaying only on sale products, regular products, downloadable products, and virtual products.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'number_of_products' => array(
                    'title' => __('Number of Products', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'number',
                    'id' => 'fcwc_product[fcwc_number_of_products]',
                    'default' => 10,
                    'custom_attributes' => array(
                        'min' => 1,
                        'max' => 100,
                        'step' => 1
                    )
                ),
                'show_product_image' => array(
                    'title' => __('Show Product Image', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'checkbox',
                    'desc' => __('Show product image in the list.', 'fullscreen-cart-for-woocommerce'),
                    'id' => 'fcwc_product[fcwc_show_product_image]',
                    'default' => 'yes'
                ),
                'show_product_description' => array(
                    'title' => __('Show Product Description', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'checkbox',
                    'desc' => __('Show product description in the list.', 'fullscreen-cart-for-woocommerce'),
                    'id' => 'fcwc_product[fcwc_show_product_description]',
                    'default' => 'yes'
                ),
                'show_product_price' => array(
                    'title' => __('Show Product Price', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'checkbox',
                    'desc' => __('Show product price in the list.', 'fullscreen-cart-for-woocommerce'),
                    'id' => 'fcwc_product[fcwc_show_product_price]',
                    'default' => 'yes'
                ),
                'buy_button_text' => array(
                    'title' => __('Buy Now Button Text', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'text',
                    'id' => 'fcwc_product[fcwc_buy_button_text]',
                    'default' => __('Buy Now', 'fullscreen-cart-for-woocommerce')
                ),
                'section_end' => array(
                    'type' => 'sectionend',
                    'id' => 'fcwc_products_settings'
                )
            );

            return apply_filters('fcwc_product_settings', $settings);
        }

        private function get_shopping_cart_settings() {
            $settings = array(
                'title' => array(
                    'title' => __('Shopping Cart Settings', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'fcwc_checkout_settings'
                ),
                'shopping_cart_section_title' => array(
                    'title' => __('Shopping Cart Section Title', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'text',
                    'id' => 'fcwc_cart[fcwc_shopping_cart_section_title]',
                    'default' => __('Shopping Cart', 'fullscreen-cart-for-woocommerce')
                ),
                'show_coupon_apply_form' => array(
                    'title' => __('Show Coupon Apply Form', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'checkbox',
                    'desc' => __('Show the form to apply coupons.', 'fullscreen-cart-for-woocommerce'),
                    'id' => 'fcwc_cart[fcwc_show_coupon_apply_form]',
                    'default' => 'yes'
                ),
                'empty_cart_message' => array(
                    'title' => __('Empty Cart Message', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'text',
                    'id' => 'fcwc_cart[fcwc_empty_cart_message]',
                    'default' => __('Your cart is empty.', 'fullscreen-cart-for-woocommerce')
                ),
                'show_go_to_cart_button' => array(
                    'title' => __('Show Cart Button', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'checkbox',
                    'desc' => __('Show the button to go to the cart.', 'fullscreen-cart-for-woocommerce'),
                    'id' => 'fcwc_cart[fcwc_show_go_to_cart_button]',
                    'default' => 'yes'
                ),
                'go_to_cart_button_text' => array(
                    'title' => __('Go To Cart Button Text', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'text',
                    'id' => 'fcwc_cart[fcwc_go_to_cart_button_text]',
                    'default' => __('Shopping Cart', 'fullscreen-cart-for-woocommerce')
                ),
                'show_go_to_checkout_button' => array(
                    'title' => __('Show Checkout Button', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'checkbox',
                    'desc' => __('Show the button to go to the checkout.', 'fullscreen-cart-for-woocommerce'),
                    'id' => 'fcwc_cart[fcwc_show_go_to_checkout_button]',
                    'default' => 'yes'
                ),
                'go_to_checkout_button_text' => array(
                    'title' => __('Checkout Button Text', 'fullscreen-cart-for-woocommerce'),
                    'type' => 'text',
                    'id' => 'fcwc_cart[fcwc_go_to_checkout_button_text]',
                    'default' => __('Checkout Now', 'fullscreen-cart-for-woocommerce')
                ),
                'calculate_shipping' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_calculate_shipping',
                    'title'       => esc_html__( 'Calculate Shipping', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION,
                    'description' => esc_html__( 'Upgrade to the Pro version to enable calculate shipping on fullscreencart.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'change_address_button_text' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_change_address_button_text',
                    'title'       => esc_html__( 'Change Address Button Text', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION,
                    'description' => esc_html__( 'Upgrade to the Pro version to customize the "Change Address" button text.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'enable_one_page_checkout' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_enable_one_page_checkout',
                    'title'       => esc_html__( 'Enable One Page Checkout', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION,
                    'description' => esc_html__( 'Upgrade to the Pro version to Enable one page checkout.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'section_end' => array(
                    'type' => 'sectionend',
                    'id' => 'fcwc_checkout_settings'
                )
            );
            return apply_filters('fcwc_shopping_cart_settings', $settings);

        }

        private function get_style_settings() {
            $settings = array(
                'cart_style_title' => array(
                    'title' => __( 'Cart Icon Count Style', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'fcwc_cart_styles',
                ),
                'cart_icon_size'    => array(
                    'type'        => 'height_width_field',
                    'id'          => 'fcwc_style[fcwc_cart_icon_size]',
                    'title'       => __(' Dimensions', 'fullscreen-cart-for-woocommerce'),
                    'default'     => [
                        'height' => '35',
                        'width'  => '35',
                    ],
                    'desc'        => __('Set the dimensions for your cart icon.', 'fullscreen-cart-for-woocommerce'),
                ),
                'cart_icon_text_color' => array(
                    'title' => __( 'Text Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_cart_icon_text_color]',
                ),
                'cart_icon_background_color' => array(
                    'title' => __( 'Background Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_cart_icon_background_color]',
                ),
                'cart_style_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_cart_styles',
                ),

                // Buy Now Button Style Section
                'buy_now_style_title' => array(
                    'title' => __( 'Products Buy Now Button Style', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'fcwc_buy_now_styles',
                ),
                'buy_now_button_text_color' => array(
                    'title' => __( 'Text Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_buy_now_button_text_color]',
                ),
                'buy_now_button_background_color' => array(
                    'title' => __( 'Background Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_buy_now_button_background_color]',
                ),
                'buy_now_style_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_buy_now_styles',
                ),

                // Shipping Button Style Section
                'shipping_button_style_title' => array(
                    'title' => __( 'Go to Cart Button Style', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'fcwc_shipping_button_styles',
                ),
                'shipping_button_text_color' => array(
                    'title' => __( 'Text Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_shipping_button_text_color]',
                ),
                'shipping_button_background_color' => array(
                    'title' => __( 'Background Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_shipping_button_background_color]',
                ),
                'shipping_button_style_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_shipping_button_styles',
                ),

                // Checkout Button Style Section
                'checkout_style_title' => array(
                    'title' => __( 'Go to Cart Checkout Button Style', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'fcwc_checkout_styles',
                ),
                'checkout_button_text_color' => array(
                    'title' => __( 'Text Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_checkout_button_text_color]',
                ),
                'checkout_button_background_color' => array(
                    'title' => __( 'Background Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_checkout_button_background_color]',
                ),
                'checkout_style_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_checkout_styles',
                ),

                // Checkout Button Style Section
                'coupon_apply_style_title' => array(
                    'title' => __( 'Coupon Apply Button Style', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'fcwc_coupon_apply_styles',
                ),
                'coupon_apply_button_text_color' => array(
                    'title' => __( 'Text Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_coupon_apply_button_text_color]',
                ),
                'coupon_apply_button_background_color' => array(
                    'title' => __( 'Background Color', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'color_combo_field',
                    'id'    => 'fcwc_style[fcwc_coupon_apply_button_background_color]',
                ),
                'coupon_apply_style_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_coupon_apply_styles',
                ),

                'update_address_style_title'=> array(
                    'title' => __( 'Change Address Button Style', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'fcwc_update_address_style_title',
                ),
                'update_address_button_text_color' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_update_address_button_text_color',
                    'title'       => esc_html__( 'Text Color', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION, // Add the Pro version link here
                    'description' => esc_html__( 'Upgrade to the Pro version to unlock customizable text color for the Update Address button.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'update_address_button_background_color' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_update_address_button_background_color',
                    'title'       => esc_html__( 'Background Color', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION, // Add the Pro version link here
                    'description' => esc_html__( 'Upgrade to the Pro version to unlock customizable background color for the Update Address button.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'update_address_style_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_update_address_style_title',
                ),

                
                'place_order_style_title'=> array(
                    'title' => __( 'Place Order Button Style', 'fullscreen-cart-for-woocommerce' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'fcwc_place_order_style_title',
                ),
                'place_order_button_text_color' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_place_order_button_text_color',
                    'title'       => esc_html__( 'Text Color', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION, // Add the Pro version link here
                    'description' => esc_html__( 'Upgrade to the Pro version to unlock customizable text color for the Update Address button.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'place_order_button_background_color' => array(
                    'type'        => 'buy_pro_button',
                    'id'          => 'fcwc_place_order_button_background_color',
                    'title'       => esc_html__( 'Background Color', 'fullscreen-cart-for-woocommerce' ),
                    'button_text' => esc_html__( 'Buy Pro', 'fullscreen-cart-for-woocommerce' ),
                    'pro_link'    => FCWC_PRO_VERSION, // Add the Pro version link here
                    'description' => esc_html__( 'Upgrade to the Pro version to unlock customizable background color for the Update Address button.', 'fullscreen-cart-for-woocommerce' ),
                    'row_class'   => 'buy-pro-button-row',
                ),
                'place_order_style_end' => array(
                    'type' => 'sectionend',
                    'id'   => 'fcwc_place_order_style_title',
                ),
                
            );

            return apply_filters('fcwc_style_settings', $settings);
        }

        private function get_product_categories() {
            $categories = get_terms( 'product_cat' );
            $options = array();
            if ( ! empty( $categories ) ) :
                foreach ($categories as $category) :
                    $options[$category->term_id] = $category->name;
                endforeach;
            endif;
            return $options;
        }
        

    }
    
    new FCWC_Settings_Fullscreen_Cart();

endif;

