<?php 

// Check if the function is already declared to avoid redeclaration errors
if ( ! function_exists( 'fcwc_get_products' ) ) {

    /**
     * Retrieves WooCommerce products based on specific settings.
     *
     * This function queries WooCommerce products using the `WC_Product_Query` class. 
     * It applies settings from the WordPress options table and allows developers to modify 
     * the query arguments using the `fcwc_get_products_args` filter.
     *
     * @return array An array of WooCommerce products.
     */
    function fcwc_get_products( $settings ) {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WC_Product' ) ) {
            return []; // Return an empty array if WooCommerce isn't active
        }
    
        // Default values in case settings are not properly configured
        $number_of_products = isset( $settings['fcwc_number_of_products'] ) ? $settings['fcwc_number_of_products'] : 10;
        $category           = isset( $settings['fcwc_product_category'] ) ? $settings['fcwc_product_category'] : [];
    
        // Define WP_Query arguments
        $args = apply_filters( 
            'fcwc_get_products_args',
            array(
                'post_type'           => 'product',           // Only query products
                'posts_per_page'      => $number_of_products, // Number of products to retrieve
                'post_status'         => 'publish',           // Only published products
                'fields'              => 'ids',               // Return only the IDs
                'tax_query'           => [
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product_type',
                        'field'    => 'slug',
                        'terms'    => array( 'simple' ),
                        'operator' => 'IN',
                    )
                ],                   // Initialize tax_query
                'meta_query'          => [
                    'relation' => 'OR'
                ],                   // Initialize tax_query
            ),
            $settings
        );
    
        // Handle category if specified
        if ( ! empty( $category ) ) :
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => $category,
                'operator' => 'IN',
            );
        endif;
        $query = new WP_Query( $args );
        // Return the product IDs
        return $query->posts; // This will return an array of product IDs
    }
    
}