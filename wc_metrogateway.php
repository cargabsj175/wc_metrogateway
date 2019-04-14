<?php
// wc_metrogateway.php: fichero principal
// Asegurarse que WooCommerce esté activo
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
    return;

/*
Plugin Name: VegnuX Metropago Gateway
Plugin URI: https://github.com/cargabsj175/wc_metrogateway
Description: Pagos con tarjeta de crédito con metropago
Version: 0.5.1
Author: Carlos Sanchez, Sergio Galvis
Author URI: https://elsimpicuitico.wordpress.com
License: GPL3
*/

define('MWC_ROOT', dirname(__FILE__));
define('VegnuX_INDEX', plugin_dir_url(__FILE__));
define('VegnuX_TXTDOM', 'wc_metrogateway');

add_action('plugins_loaded', 'mwc_load_textdomain');
function mwc_load_textdomain(){
	load_plugin_textdomain('VegnuX_TXTDOM', false, plugin_basename(dirname(__FILE__)) . '/langs');
}

// configs del lado de Woocomerce
require_once MWC_ROOT . '/include/vmpgateway_settings.php';
// configs del lado del Usuario
require_once MWC_ROOT . '/include/vmpgateway_user_wc_tab.php';
// snippets o personalizaciones
require_once MWC_ROOT . '/include/vmpgateway_snippets.php';