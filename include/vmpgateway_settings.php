<?php
// vmpgateway_settings.php: Establece los valores de Metropago
// Elementos del menu de configuracion de la pasarela en Woocommerce

add_action('plugins_loaded', 'vegnux_define_gateway_class');

function vegnux_define_gateway_class(){

	class Vegnux_Gateway extends WC_Payment_Gateway{
	
		function __construct(){

			$this->auth_expires = 20;
			$this->adjust_delay = 5;

			$this->log_errors = true;
			$this->log_errors_file = dirname(__FILE__) . '/failed_transactions';

			$this->id = 'vegnux_gateway';
			$this->icon = 'Vegnux_INDEX' . 'src/ccards_logos.png';
			$this->has_fields = true;
			$this->method_title = __('VegnuX Metropago Gateway', 'Vegnux_TXTDOM' );
			$this->method_description = __('Direct payments with VegnuX Metropago Gateway. User will be asked to enter credit card details on the checkout page.', 'Vegnux_TXTDOM');
			
			 //Initialize form methods
			$this->init_form_fields();
			$this->init_settings();
			
			 // Define user set variables.
            $this->acc_code = $this->settings['acc_code'];
            $this->merchant_id = $this->settings['merchant_id'];
            $this->terminal_id = $this->settings['terminal_id'];
            $this->enviroment = $this->settings['enviroment'];

			$this->title = $this->get_option('title');

			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		}

		function init_form_fields(){
			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable Matropago', 'Vegnux_TXTDOM'),
					'type' => 'checkbox',
					'label' => __('Enable', 'Vegnux_TXTDOM'),
					'default' => 'yes'
				),
				'title' => array(
					'title' => __('Method name', 'Vegnux_TXTDOM'),
					'type' => 'text',
					'default' => __('VegnuX Metropago Gateway', 'Vegnux_TXTDOM'),
					'desc_tip' => true
				),
				'description' => array(
					'title' => __('Method description', 'Vegnux_TXTDOM'),
					'type' => 'textarea',
					'default' =>  __('Use this method to pay with your credit card securely.', 'Vegnux_TXTDOM')
				),
				'acc_code' => array(
					'title' => __('AccCode', 'Vegnux_TXTDOM'),
					'type' => 'text',
					'default' => '123123',
				),
				'merchant_id' => array(
					'title' => __('Merchant', 'Vegnux_TXTDOM'),
					'type' => 'text',
					'default' => '100177',
				),
				'terminal_id' => array(
					'title' => __('Terminal', 'Vegnux_TXTDOM'),
					'type' => 'text',
					'default' => '100177001',
				),
				'enviroment' => array(
					'title' => __('Enviroment', 'Vegnux_TXTDOM'),
					'type' => 'select',
					'default' => 'SANDBOX',
					'options' => array(
						'SANDBOX' => __('Sandbox', 'Vegnux_TXTDOM'),
						'PRODUCTION' => __('Production', 'Vegnux_TXTDOM')
						)
				)
			);
			

		}
		
// Aqui continuamos

	}
}


function vegnux_declare_gateway_class($methods){
	$methods[] = 'Vegnux_Gateway';
	return $methods;
}
add_filter('woocommerce_payment_gateways', 'vegnux_declare_gateway_class');
