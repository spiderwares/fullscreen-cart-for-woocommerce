<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'FCWC_Fullscreen_Cart' ) ) :

    /**
     * Main FCWC_Fullscreen_Cart Class
     *
     * @class FCWC_Fullscreen_Cart
     * @version 1.0.0
     */
    final class FCWC_Fullscreen_Cart {

        /**
         * The single instance of the class.
         *
         * @var FCWC_Fullscreen_Cart
         */
        protected static $instance = null;

        /**
         * Constructor for the class.
         */
        public function __construct() {
            $this->init_hooks();
            $this->includes();
        }

        /**
         * Initialize hooks and filters.
         */
        private function init_hooks() {
            add_action( 'elementor/widgets/register', [ $this, 'register_elementor_widget' ] );

            // Hook to install the plugin after plugins are loaded
            add_action( 'fcwc_init', array( $this, 'includes' ), 11 );
            add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'dynamic_styles' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'fcwc_enqueue_backend_styles' ) );
        }

        /**
         * Function to display admin notice if WooCommerce is not active.
         */
        public function admin_notice() {
            ?>
            <div class="error">
                <p><?php esc_html_e( 'Fullscreen Cart for WooCommerce is enabled but not effective. It requires WooCommerce to work.', 'fullscreen-cart-for-woocommerce' ); ?></p>
            </div>
            <?php
        }

        /**
         * Execute function on plugin activation
         */
        public static function activate() {
            // Include the file containing the default options
            $defaultOptions = require_once FCWC_PATH . '/includes/static/fcwc-default-options.php';

            // Loop through each default option
            foreach ( $defaultOptions as $optionKey => $option ) :
                // Get the existing option value
                $existingOption = get_option( $optionKey );

                // If the option is not set, update it with the default value
                if ( ! $existingOption ) :
                    update_option( $optionKey, $option );
                endif;
            endforeach;
        }

        /**
         * Function to initialize the plugin after WooCommerce is loaded.
         */
        public function fcwc_install() {
            if ( ! function_exists( 'WC' )  ) : // Check if WooCommerce is active.
                add_action( 'admin_notices', array( $this, 'admin_notice' ) ); // Display admin notice if WooCommerce is not active.
            else :
                do_action( 'fcwc_init' ); // Initialize the plugin.
            endif;
        }

        /**
         * Main FCWC_Fullscreen_Cart Instance.
         *
         * Ensures only one instance of FCWC_Fullscreen_Cart is loaded or can be loaded.
         *
         * @static
         * @return FCWC_Fullscreen_Cart - Main instance.
         */
        public static function instance() {
            if ( is_null( self::$instance ) ) :
                self::$instance         = new self();

                /**
                 * Fire a custom action to allow dependencies
                 * after the successful plugin setup
                 */
                do_action( 'fcwc_plugin_loaded' );
            endif;
            return self::$instance;
        }

        /**
         * Include required files.
         *
         * @access private
         */
        public function includes() {
            /**
             * Core
             */
            require_once FCWC_PATH . 'includes/class-fcwc-install.php'; 
            require_once FCWC_PATH . 'includes/fcwc-functions.php';
            require_once FCWC_PATH . 'includes/class-fcwc-cart-controller.php';
            require_once FCWC_PATH . 'includes/admin/class-fcwc-setting-tab.php';
            require_once FCWC_PATH . 'includes/class-fcwc-wc-extends.php';
            require_once FCWC_PATH . 'includes/class-fcwc-floating-cart.php';
        }

        function dynamic_styles() {
            $style = get_option( 'fcwc_style' );
			ob_start();
			wc_get_template( 
                'dynamic-styles.php',
                array(
                    'style' => $style
                ),
                'fullscreen-cart-for-woocommerce/',
                FCWC_TEMPLATE_PATH
            );
			$output = ob_get_clean();

            return apply_filters('fcwc_dynamic_styles', $output, $style);
        }
        

        /**
         * include widgets
         */
        function register_assets() {
            $styles = $this->dynamic_styles();
            wp_enqueue_style( 'fcwc-fullscreen-cart-style', FCWC_URL . 'assets/widget.css', [], time() );
            wp_enqueue_script( 'fcwc-fullscreen-cart-script', FCWC_URL . 'assets/widget.js', [ 'jquery' ], time(), true );
            wp_add_inline_style( 'fcwc-fullscreen-cart-style', $styles );
            
            wp_enqueue_script( 'wc-cart' );
            wp_enqueue_script('wc-cart-fragments');
        }

        // Hook to enqueue styles in the backend
        public function fcwc_enqueue_backend_styles() {
            wp_enqueue_style( 'fcwc-backend-style', FCWC_URL . 'assets/fcwc-backend.css', array(),  time() );
        }


        /**
         * Elementor Fullscreen Cart Widget
         */
        public function register_elementor_widget( $widgets_manager ) {
            require_once FCWC_PATH . 'includes/public/class-fcwc-elementor-widget.php';
            $widgets_manager->register( new FCWC_Elementor_Widget() );
        }
        
    }

endif;
