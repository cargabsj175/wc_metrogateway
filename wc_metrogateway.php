<?php

// Asegurarse que WooCommerce esté activo
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
    return;

/*
Plugin Name: VegnuX WC Metropago Gateway
Plugin URI: https://vegnux.org.ve
Description: Pagos con tarjeta de crédito a través de metropago
Version: 0.1
Author: Carlos Sanchez, Julio Terán
Author URI: https://vegnux.org.ve
License: GPL3
*/

?>
