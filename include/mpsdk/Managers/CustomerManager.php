<?php

/**
 * CustomerManager short summary.
 * Customer related methods
 *
 * CustomerManager description.
 * class contains all methods related to customer.
 * methods receives parameters or model and sends calls to the Base Manager.
 * response returned from the Base Manger is returned back to the calling method.
 * Incase of error, error is formatted and returned.
 * 
 *
 * @version 1.0
 * @author Raza
 */
include_once("BaseManager.php");



include_once MWC_ROOT . "/include/mpsdk/Entities/Customer.php";

include_once MWC_ROOT . "/include/mpsdk/Entities/CustomerResponse.php";

include_once MWC_ROOT . "/include/mpsdk/Entities/CreditCard.php";

include_once MWC_ROOT . "/include/mpsdk/Entities/CreditCardResponse.php";
class CustomerManager extends BaseManager
{
    public function __construct(MetropagoGateway $metropagoGateway) {        
        parent::__construct($metropagoGateway);
    }

    public function AddCustomer($customer){
        $customer->CustomerId = "0";
        return $this->SaveCustomer($customer);
    }

    public function UpdateCustomer($customer){
        return $this->SaveCustomer($customer);
    }

    public function UpdateCard($cardDetail){
        try{
            $Response = $this->SendAPICurlRequest($cardDetail, $this->MetropagoObject->GatewayURL . "Card/SaveCreditCardInformation", "POST");     
            if($Response == null){
                $cardDetail->ResponseDetails = $this->ErrorResponse(new CreditCardResponse(),99,"Response object is null");                
            }
            else if($Response["ResponseMessage"] == null){
                $cardDetail->ResponseDetails = $this->ErrorResponse(new CreditCardResponse(),99,"Response message is null");                
            }
            else{
                return json_decode($Response["ResponseMessage"]);
            }
        }
        catch(Exception $ex){
            //$cardDetail->ResponseDetails = $this->ErrorResponse(new CreditCardResponse(),99,$ex->getMessage());
            $cardDetail->ResponseDetails = $this->ErrorResponse(new CreditCardResponse(),99,"Internal server error");
        }
        return $cardDetail;
    }

    private function SaveCustomer($customer){
        try{
            $Response = $this->SendAPICurlRequest($customer, $this->MetropagoObject->GatewayURL . "Customer/SaveCustomerInformation", "POST");                 
            if($Response == null){
                $customer->ResponseDetails=  $this->ErrorResponse( new CustomerResponse(),99,"Response object is null");
            }
            else if($Response["ResponseMessage"] == null){
                $customer->ResponseDetails = $this->ErrorResponse(new CreditCardResponse(),99,"Response message is null");                
            }
            else{
                return json_decode($Response["ResponseMessage"]);
            }
        }
        catch(Exception $ex){
            //$customer->ResponseDetails=  $this->ErrorResponse( new CustomerResponse(),99,$ex->getMessage());
            $customer->ResponseDetails=  $this->ErrorResponse( new CustomerResponse(),99,"Internal server error");
        }
        return $customer;
    }
    
    public function SearchCustomer($searchFilter){
        $Result = array();
        try{
            $Response = $this->SendAPICurlRequest($searchFilter, $this->MetropagoObject->GatewayURL . "Customer/GetCustomersByFilter", "POST");     
            if($Response == null || $Response["ResponseMessage"] == null){
                return $Result;
            }
            else{
                $Result = json_decode($Response["ResponseMessage"]);
            }
        }
        catch(Exception $ex){
            //$cardDetail->ResponseDetails = $this->ErrorResponse(new CreditCardResponse(),99,$ex->getMessage());
            return $Result;
        }
        return $Result;
    }
}
