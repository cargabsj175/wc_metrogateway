<?php

// Asegurarse que WooCommerce esté activo
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
    return;

/*
Plugin Name: VegnuX Metropago Gateway
Plugin URI: https://vegnux.org.ve
Description: Pagos con tarjeta de crédito con metropago
Version: 0.2.1
Author: Carlos Sanchez, Julio Terán
Author URI: https://vegnux.org.ve
License: GPL3
*/

define('MWC_ROOT', dirname(__FILE__));
define('MWC_INDEX', plugin_dir_url(__FILE__));
define('MWC_TXTDOM', 'wc_metrogateway');

add_action('plugins_loaded', 'mwc_load_textdomain');
function mwc_load_textdomain(){
	load_plugin_textdomain(VegnuX_TXTDOM, false, plugin_basename(dirname(__FILE__)) . '/langs');
}

require MWC_ROOT . '/include/vmpgateway_settings.php';
require MWC_ROOT . '/include/vmpgateway_user_wc_tab.php';

?>
