<?php

// Nota: Actualizar Permalinks o tendra error 404
 
function vegnux_add_payment_settings_endpoint() {
    add_rewrite_endpoint( 'payment-settings', EP_ROOT | EP_PAGES );
}
 
add_action( 'init', 'vegnux_add_payment_settings_endpoint' );
 
 
// ------------------
// 2. Add new query var
 
function vegnux_payment_settings_query_vars( $vars ) {
    $vars[] = 'payment-settings';
    return $vars;
}
 
add_filter( 'query_vars', 'vegnux_payment_settings_query_vars', 0 );
 
 
// ------------------
// 3. Insert the new endpoint into the My Account menu
 
function vegnux_add_payment_settings_link_my_account( $items ) {
    $items['payment-settings'] = 'Configuraci&oacute;n de pago';
    return $items;
}
 
add_filter( 'woocommerce_account_menu_items', 'vegnux_add_payment_settings_link_my_account' );
 
 
// ------------------
// 4. Add content to the new endpoint
 
function vegnux_payment_settings_content() {
echo '<h3>Premium WooCommerce Support</h3><p>Welcome to the WooCommerce support area. As a premium customer, you can submit a ticket should you have any WooCommerce issues with your website, snippets or customization. <i>Please contact your theme/plugin developer for theme/plugin-related support.</i></p>';
echo do_shortcode( ' /* your shortcode here */ ' );
}
 
add_action( 'woocommerce_account_payment-settings_endpoint', 'vegnux_payment_settings_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format
