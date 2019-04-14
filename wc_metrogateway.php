<?php
// wc_metrogateway.php: fichero principal
// Asegurarse que WooCommerce esté activo
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
    return;
/*
Plugin Name: VegnuX Metropago Gateway
Plugin URI: https://github.com/cargabsj175/wc_metrogateway
Description: Payments using metropago gateway.
Version: 0.6
Author: Carlos Sanchez, Sergio Galvis
Author URI: https://elsimpicuitico.wordpress.com
Text Domain: wc_metrogateway
Domain Path: /include/langs
License: GPL3
*/

define('MWC_ROOT', dirname(__FILE__));

function wc_metrogateway_load_textdomain() {
load_plugin_textdomain( 'wc_metrogateway', false, plugin_basename(dirname(__FILE__)) . '/langs/' );
}

add_action( 'plugins_loaded', 'wc_metrogateway_load_textdomain' );

// configs del lado de Woocomerce
require_once MWC_ROOT . '/include/vmpgateway_settings.php';
// configs del lado del Usuario
require_once MWC_ROOT . '/include/vmpgateway_user_wc_tab.php';
// snippets o personalizaciones
require_once MWC_ROOT . '/include/vmpgateway_snippets.php';