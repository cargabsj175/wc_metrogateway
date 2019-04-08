<?php
// vmpgateway_settings.php: Configuraciones en Ajustes -> Pagos
// Elementos del menu de configuracion de la pasarela en Woocommerce

// Llamamos al SDK de Metropago
include_once MWC_ROOT . "/include/mpsdk/Configuration/MetropagoGateway.php";

include_once MWC_ROOT . "/include/mpsdk/Managers/CustomerManager.php";

include_once MWC_ROOT . "/include/mpsdk/Entities/Customer.php";
include_once MWC_ROOT . "/include/mpsdk/Entities/Address.php";
include_once MWC_ROOT . "/include/mpsdk/Entities/CreditCard.php";
include_once MWC_ROOT . "/include/mpsdk/Entities/CustomerSearch.php";
include_once MWC_ROOT . "/include/mpsdk/Entities/ParameterFilter.php";
include_once MWC_ROOT . "/include/mpsdk/Entities/CustomerEntity.php";
include_once MWC_ROOT . "/include/mpsdk/Entities/Instruction.php";
include_once MWC_ROOT . "/include/mpsdk/Entities/CustomerSearch.php";
include_once MWC_ROOT . "/include/mpsdk/Entities/CustomerSearchOption.php";
include_once MWC_ROOT . "/include/mpsdk/Entities/Instruction.php";

add_action('plugins_loaded', 'vegnux_define_gateway_class');

function vegnux_define_gateway_class(){

	class Vegnux_Gateway extends WC_Payment_Gateway{
	
		function __construct(){

			$this->auth_expires = 20;
			$this->adjust_delay = 5;

			$this->log_errors = true;
			$this->log_errors_file = dirname(__FILE__) . '/failed_transactions';

			$this->id = 'vegnux_gateway';
			$this->icon = plugins_url( '../src/ccards_logos.png', __FILE__ );
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
		
/* inicio de los campos y procesos de pagos */


function payment_fields() {
    
// llamamos las variables de usuario de wordpress
	  global $current_user;
      wp_get_current_user();
/* guardamos customer y documento de identidad como variables*/
$usercustomerid = get_user_meta( $current_user->ID, 'vmpuser_cusID' , true);
$useruniqueid = get_user_meta( $current_user->ID, 'vmpuser_perID' , true);
/* Obtenemos valores del gateway mediante el gateway id */
$payment_gateway_id = 'vegnux_gateway';
/* Obtenemos instancia del objeto WC_Payment_Gateways */
$payment_gateways = WC_Payment_Gateways::instance();
/* Obtenemos el objeto WC_Payment_Gateway deseado */
$payment_gateway = $payment_gateways->payment_gateways()[$payment_gateway_id];
    
echo __('<h3>Choose a Card</h3>', 'Vegnux_TXTDOM' );

	/*=========== INSTANCIACION DE METROPAGO ===============*/
			$sdk = new MetropagoGateway("$payment_gateway->enviroment","$payment_gateway->merchant_id","$payment_gateway->terminal_id","","");
			$CustManager  = new CustomerManager($sdk);

			$customerFilters =new CustomerSearch();
			$customerFilters->CustomerId = $usercustomerid;
			$customerFilters->UniqueIdentifier= $useruniqueid;

			$customerSearchOptions = new CustomerSearchOption();
			$customerSearchOptions->IncludeCardInstruments=true;
			$customerSearchOptions->IncludeShippingAddress=true;
			$customerFilters->SearchOption=$customerSearchOptions;

			$response_customers = $CustManager->SearchCustomer($customerFilters);


			foreach ($response_customers[0]->CreditCards as $card ) {
				if( $card->CardType == 'Visa'){
					$logo = plugins_url( '../src/visa.png', __FILE__ );
				}else{
					$logo = plugins_url( '../src/mastercard.png', __FILE__ );
				}

				echo '<div class="row" style="padding:15px; border-bottom:1px solid #E5E5E5">
						<div class="col-md-3">
							<img src="'.$logo.'" style="width:60px;">
							
						</div>
						<div class="col-md-9">
							'.$card->Number.'
						</div>
					 </div>';
			}

}
 
function process_payment( $order_id ) {
 
	global $woocommerce;
 
	// necesitamos esto para obtener cualquier detalle de la orden
	$order = wc_get_order( $order_id );
 
	/*
 	 * Arreglo con los parametros del SDK
	 */
	 
	//$args = array(
 
	$payment = APPROVED;
 
	//);
 
	/*
	 * Construimos la interaccion del SDK con wp_remote_post()
 	 */
	 $response = wp_remote_post( '{payment processor endpoint}', $args );
 
 
	 if( !is_wp_error( $response ) ) {
 
		 $body = json_decode( $response['body'], true );
        // esto puede ser diferente dependiendo del procesador de pago
		 if ( $body['response']['responseCode'] == 'APPROVED' ) {
 
			// recibimos el pago
			$order->payment_complete();
			$order->reduce_order_stock();
 
			// Notas al cliente (customer) (reemplace true con false para hacerlo privado)
			$order->add_order_note( 'Hola, su orden ha sido pagada. Muchas Gracias', true );
 
			// Vaciar el carrito
			$woocommerce->cart->empty_cart();
 
			// Redireccionar a la pagina de Gracias por su compra
			return array(
				'result' => 'success',
				'redirect' => $this->get_return_url( $order )
			);
 
		 } else {
			wc_add_notice(  'Intente de nuevo.', 'error' );
			return;
		}
 
	} else {
		wc_add_notice(  'Error de conexi&oacute;n.', 'error' );
		return;
	}
 
}


/* fin de los campos y procesos de pagos */

	}
}


function vegnux_declare_gateway_class($methods){
	$methods[] = 'Vegnux_Gateway';
	return $methods;
}
add_filter('woocommerce_payment_gateways', 'vegnux_declare_gateway_class');
