<?php

/**
 * Transaction short summary.
 * Transaction model
 *
 * Transaction description.
 * Transaction model definition
 *
 * @version 1.0
 * @author Raza
 */
include_once("TransactionResponse.php");

class Transaction
{
    public $PaymentMethodToken = "";
    public $Amount = "";
    public $BillingAddressId = "";
    public $TerminalId = "";
    public $OrderTrackingNumber = "";
    public $CustomerId = "";
    public $OrderId = "";
    public $ShippingAddressId = "";
    public $TransactionId = "";
    public $CreditCardDetail = null;
    public $ShippingAddress = null;
    public $BillingAddress = null;
    public $ResponseDetails = null;
    public $TransactOptions = null;
    public $CustomFields = null;
    public $CustomerData = null;
    public $ThirdPartyDescription= "";
    public $ThirdPartyStatus= null;
    public $CustomerEntityDetail= null;
    

    function __construct() {
        $this->ResponseDetails  = new TransactionResponse();
    }

    
    function __destruct() {     
        unset($this->ResponseDetails);        
        
    }
}
