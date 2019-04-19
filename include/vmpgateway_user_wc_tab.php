<?php
// vmpgateway_user_wc_tab.php: Crea pestaña de la wallet en perfil de usuario
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
    $items['payment-settings'] = __('Wallet', 'wc_metrogateway' );
    return $items;
}
 
add_filter( 'woocommerce_account_menu_items', 'vegnux_add_payment_settings_link_my_account' );
 
 
// ------------------
// 4. Add content to the new endpoint
 
function vegnux_payment_settings_content() {
echo "<h3>".__('Credit Cards Settings', 'wc_metrogateway' )."</h3>";

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
    
     echo '<br>';
     echo __( 'It&#39;s the first time that you enter this module, please enter a unique identification number before adding credit cards.  &#40;Example&#58; passport, driver&#39;s license or another valid document&#41;', 'wc_metrogateway' );
     echo '<br><br>';
	 echo '<form onsubmit="setTimeout(function () { window.location.reload(); }, 5)" method="post">
		<input name="idCustomer" class="form-control form-control-lg" type="text" placeholder="' .__('Document id', 'wc_metrogateway') . '">
		<button type="submit" class="btn btn-secondary" style="margin:10px 5px;">' .__('Create Wallet', 'wc_metrogateway'). '</button>
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
	  }

}else{

	echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="margin:20px 10px;">
			 	 ' .__('Add Card', 'wc_metrogateway'). '
			</button>

			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      
			      <div class="modal-body">
			        <form method="post">
						<input name="cardName" class="form-control form-control-lg" type="text"  placeholder="' .__('Cardholder name', 'wc_metrogateway'). '" value="'.$current_user->user_firstname.' '.$current_user->user_lastname.'" required><br>
						
						<input name="cardNumber" class="form-control form-control-lg" type="text" pattern="\d+" autocomplete="off" maxlength="16" placeholder="' .__('Card Number', 'wc_metrogateway'). '" required><br>
						
						<select class="form-control form-control-lg" name="cardMonth" autocomplete="off" required>
                            <option value="" selected="selected">'.__('Month', 'wc_metrogateway').'</option>
                            <option value="01">01 - '.__('January', 'wc_metrogateway').'</option>
                            <option value="02">02 - '.__('February', 'wc_metrogateway').'</option>
                            <option value="03">03 - '.__('March', 'wc_metrogateway').'</option>
                            <option value="04">04 - '.__('April', 'wc_metrogateway').'</option>
                            <option value="05">05 - '.__('May', 'wc_metrogateway').'</option>
                            <option value="06">06 - '.__('June', 'wc_metrogateway').'</option>
                            <option value="07">07 - '.__('July', 'wc_metrogateway').'</option>
                            <option value="08">08 - '.__('August', 'wc_metrogateway').'</option>
                            <option value="09">09 - '.__('September', 'wc_metrogateway').'</option>
                            <option value="10">10 - '.__('October', 'wc_metrogateway').'</option>
                            <option value="11">11 - '.__('November', 'wc_metrogateway').'</option>
                            <option value="12">12 - '.__('December', 'wc_metrogateway').'</option>
                        </select> <br>';
                        
                        echo '<select class="form-control form-control-lg" name="cardYear" autocomplete="off" required>
                        
                        <option value="" selected="selected">'.__('Year', 'wc_metrogateway').'</option>';
                        
                        $curr_year = (int)date('y');
                            for($i=$curr_year; $i<=($curr_year+10); $i++):
                        
                            echo '<option value="'.$i.'"> 20'.$i.' </option>';
                        
                            endfor;
                        echo '</select><br>
                        
                        <input name="cardCvv" class="form-control form-control-lg" type="text" pattern="\d+" autocomplete="off" maxlength="3" placeholder="CVV" required><br>
						
						<button class="btn btn-primary" type="submit">' .__('Add Card', 'wc_metrogateway'). '</button>
						
						<button type="button" class="btn btn-secondary" data-dismiss="modal">' .__('Close', 'wc_metrogateway'). '</button>
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

}

add_action( 'woocommerce_account_payment-settings_endpoint', 'vegnux_payment_settings_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format
