<?php
// vmpgateway_user_wc_tab.php: Crea una pestaña en perfil de usuario
// Muestra las configuraciones de tarjeta de crédito para cada cliente

// Nota: Actualizar Permalinks o tendra error 404

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
    $items['payment-settings'] = __('Wallet', Vegnux_TXTDOM );
    return $items;
}
 
add_filter( 'woocommerce_account_menu_items', 'vegnux_add_payment_settings_link_my_account' );
 
 
// ------------------
// 4. Add content to the new endpoint
 
function vegnux_payment_settings_content() {
echo __('<h3>Credit Card Settings</h3>', Vegnux_TXTDOM );

echo '<form method="post">
		<input name="idCustomer" type="text" placeholder="Documento de identificacion">
		<button type="submit">Enviar</button>
	  </form>';

// llamamos las variables de usuario de wordpress
	  global $current_user;
      get_currentuserinfo();
      
// imprimimos esas variables de current_user

      echo 'User first name: ' . $current_user->user_firstname . "<br>";
      echo 'User last name: ' . $current_user->user_lastname . "<br>";
      
// Obtenemos valores de la config del gateway mediante el id
$payment_gateway_id = 'vegnux_gateway';
// Obtenemos una instancia del objeto WC_Payment_Gateways
$payment_gateways = WC_Payment_Gateways::instance();
// Obtenemos el objeto WC_Payment_Gateway deseado
$payment_gateway = $payment_gateways->payment_gateways()[$payment_gateway_id];

// Mostrar la info guardada en las configuraciones de la pasarela:
echo 'AccCode: ' . $payment_gateway->acc_code . '<br>';
echo 'Ambiente: ' . $payment_gateway->enviroment .'<br>';
echo 'MerchantID: ' . $payment_gateway->merchant_id . '<br>';
echo 'TerminalID: ' . $payment_gateway->terminal_id . '<br>';

/*  Ver el objeto completo de la pasarela 
echo '<pre>';
print_r( $payment_gateway ); 
echo '</pre>'; */

      
      

	  if( isset($_POST['idCustomer'])){

	  		$sdk = new MetropagoGateway("$payment_gateway->enviroment","$payment_gateway->merchant_id","$payment_gateway->terminal_id","","");

			$CustManager = new CustomerManager($sdk);

			$customer = new Customer();
			$customer->UniqueIdentifier =$_POST['idCustomer'];
			
			$customer->FirstName = $current_user->user_firstname;
			$customer->LastName = $current_user->user_lastname;
		
			$customerResult = $CustManager->AddCustomer($customer); 
			echo '<pre>';
			print_r($customerResult);
			echo '</pre>';
			echo '<h1>'.$customerResult->CustomerId.'</h1>';
		
	  }



// echo do_shortcode( ' /* tu shortcode aqui */ ' );
}
 
function createCostumer(){

		if( isset($_POST['idCustomer'])){
			echo 'hola';

		/*	$sdk = new MetropagoGateway(ENVIRONMENT, MERCHANTID, TERMINALID, "", "");

			$CustManager = new CustomerManager($sdk);

			$customer = new Customer();
			$customer->UniqueIdentifier =$_POST['addCostumerId'];
			$customer->Email = $_POST['addCostumerEmail'];
			$customer->FirstName = $_POST['addCostumerName'];
			$customer->LastName = $_POST['addCostumerLastName'];
			$customer->Phone = $_POST['addCostumerPhone'];
			$customerResult = $CustManager->AddCustomer($customer); 

			print_r($customerResult);
			
*/
		}		

	}


add_action( 'woocommerce_account_payment-settings_endpoint', 'vegnux_payment_settings_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format
