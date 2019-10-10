<?php
/*
* WooCommerce functions for My Account pages
*/

// Edit My account page navigation
function my_account_menu_order() {
 	$menuOrder = array(
 		'dashboard'          => __( 'Your account', 'woocommerce' ),
 		'orders'             => __( 'Previous quotes', 'woocommerce' ),
 		'downloads'          => __( 'Downloads', 'woocommerce' ),
 		'edit-address'       => __( 'Addresses', 'woocommerce' ),
 		'edit-account'    	 => __( 'Account details', 'woocommerce' ),
 		'customer-logout'    => __( 'Logout', 'woocommerce' ),
 	);
 	return $menuOrder;
 }
 add_filter ( 'woocommerce_account_menu_items', 'my_account_menu_order' );


// Max orders per page (my account)
function custom_my_account_orders( $args ) {
    $args['posts_per_page'] = -1;
    return $args;
}
add_filter( 'woocommerce_my_account_my_orders_query', 'custom_my_account_orders', 10, 1 );


// My orders columns
function new_orders_columns( $columns = array() ) {

    // Hide the columns
    if( isset($columns['order-total']) ) {
        // Unsets the columns which you want to hide
        unset( $columns['order-status'] );
    }

    return $columns;
}
add_filter( 'woocommerce_account_orders_columns', 'new_orders_columns' );


// Set Display name not required
function wc_save_account_details_required_fields( $required_fields ){
    unset( $required_fields['account_display_name'] );
    return $required_fields;
}
add_filter('woocommerce_save_account_details_required_fields', 'wc_save_account_details_required_fields' );

?>