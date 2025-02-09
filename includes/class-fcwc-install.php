<?php

/**
 * Installation related functions and actions.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'FCWC_install' ) ) :

    /**
     * FCWC_install Class
     *
     * Handles installation processes like creating database tables,
     * setting up roles, and creating necessary pages on plugin activation.
     */
    class FCWC_install {

        /**
         * Hook into WordPress actions and filters.
         */
        public static function init() {
            add_filter( 'plugin_action_links_' . plugin_basename( FCWC_FILE ), array( __CLASS__, 'plugin_action_links' ) );
        }

        /**
         * Install plugin.
         *
         * Creates tables, roles, and necessary pages on plugin activation.
         */
        public static function install() {
            if ( ! is_blog_installed() ) :
                return;
            endif;
            
        }

        /**
         * Add plugin action links.
         *
         * @param array $links Array of action links.
         * @return array Modified array of action links.
         */
        public static function plugin_action_links( $links ) {
            $action_links = array(
                'settings' => sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    admin_url( 'admin.php?page=wc-settings&tab=fcwc_fullscreen_cart' ),
                    esc_attr__( 'Fullscreen Cart Settings', 'fullscreen-cart-for-woocommerce' ),
                    esc_html__( 'Settings', 'fullscreen-cart-for-woocommerce' )
                ),
            );
            return array_merge( $action_links, $links );
        }

    }

    // Initialize the installation process
    FCWC_install::init();

endif;
