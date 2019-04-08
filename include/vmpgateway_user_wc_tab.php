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
    $items['payment-settings'] = __('Wallet', 'Vegnux_TXTDOM' );
    return $items;
}
 
add_filter( 'woocommerce_account_menu_items', 'vegnux_add_payment_settings_link_my_account' );
 
 
// ------------------
// 4. Add content to the new endpoint
 
function vegnux_payment_settings_content() {
echo __('<h3>Credit Card Settings</h3>', 'Vegnux_TXTDOM' );

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

/****VALIDAR QUE NO ESTE EN BD EL CUSTOMERID CREADO**/

if( $usercustomerid == '' ){

	 echo '<form method="post">
		<input name="idCustomer" class="form-control form-control-lg" type="text" placeholder="Documento de identificacion">
		<button type="submit" class="btn btn-secondary" style="margin:10px 5px;">Crear Customer</button>
	  </form>';

	if( isset($_POST['idCustomer'])){

	  		/*=========== INSTANCIACION DE METROPAGO ===============*/
			$sdk = new MetropagoGateway("$payment_gateway->enviroment","$payment_gateway->merchant_id","$payment_gateway->terminal_id","","");

			$CustManager = new CustomerManager($sdk);

			$customer = new Customer();
			$customer->UniqueIdentifier =$_POST['idCustomer'];
			
			$customer->FirstName = $current_user->user_firstname;
			$customer->LastName = $current_user->user_lastname;
			$customerResult = $CustManager->AddCustomer($customer);

		    /* Guardamos el idCustomer y UniqueIdentifier del usuario en bd */
		    $valor1 = $customerResult->CustomerId;
		    $valor2 = $_POST['idCustomer'];
		    update_user_meta( $current_user->ID, 'vmpuser_cusID' , $valor1 );
		    update_user_meta( $current_user->ID, 'vmpuser_perID' , $valor2 );
		    
			/*echo '<pre>';
			print_r($customerResult);
			echo '</pre>';
			echo '<h1>'.$customerResult->CustomerId.'</h1>';*/
		
	  }

}else{

	echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin:20px 10px;">
			 	 Agregar Tarjeta
			</button>

			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      
			      <div class="modal-body">
			        <form method="post">
						<input name="cardName" class="form-control form-control-lg" type="text"  placeholder="Nombre de Tarjeta" value="'.$current_user->user_firstname.' '.$current_user->user_lastname.'" required><br>
						<input name="cardNumber" class="form-control form-control-lg" type="text"  placeholder="Número de Tarjeta" required><br>
						<input name="cardMonth" class="form-control form-control-lg" type="text"  placeholder="Mes de Vencimiento" required><br>
						<input name="cardYear" class="form-control form-control-lg" type="text"  placeholder="Año de Vencimiento" required><br>
						<input name="cardCvv" class="form-control form-control-lg" type="text"  placeholder="CVV" required><br>
						<button class="btn btn-primary" type="submit">Agregar Tarjeta</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					  </form>

			      </div>
			     
			    </div>
			  </div>
			</div>';

	  if( isset($_POST['cardName'])){

			/*=========== INSTANCIACION DE METROPAGO ===============*/
			$sdk = new MetropagoGateway("$payment_gateway->enviroment","$payment_gateway->merchant_id","$payment_gateway->terminal_id","","");
			$CustManager  = new CustomerManager($sdk);

			$customer = new Customer();

			$customer->UniqueIdentifier = $useruniqueid;

			$customerRe = $CustManager->UpdateCustomer($customer);

			$customer->CreditCards =array();
			$card = new CreditCard();
			$card->CardholderName= $_POST['cardName'];
			$card->Status="Active";
			$card->ExpirationMonth=$_POST['cardMonth'];
			$card->ExpirationYear=$_POST['cardYear'];
			$card->ExpirationDate = $_POST['cardMonth'].$_POST['cardYear'];
			$card->Number= $_POST['cardNumber'];
			$card->CVV= $_POST['cardCvv'];
			$card->CustomerId= $usercustomerid;
			$card->Address=array();
			$Address =new  Address();
			$Address->AddressId = "0";
			$Address->AddressLine1 = "";
			$Address->AddressLine2 = "";
			$Address->City = "";
			$Address->CountryName = "";
			$Address->SubDivision = "";
			$Address->State = "";
			$Address->ZipCode = "";
			$card->Address =$Address;
			$customer->CreditCards[]=$card;

			$customerSavedWithCardResult = $CustManager->UpdateCustomer($customer);
			/*
			echo '-----------<pre>';
			print_r($customerSavedWithCardResult);
			echo '-----------</pre>';
			*/
		}


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


			//echo '*******************<pre>';
			//print_r($response_customers[0]->CreditCards);
			//echo '*******************</pre>';

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

	  //**** add card**//

		//search cards

// echo do_shortcode( ' /* tu shortcode aqui */ ' );
}

add_action( 'woocommerce_account_payment-settings_endpoint', 'vegnux_payment_settings_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format
