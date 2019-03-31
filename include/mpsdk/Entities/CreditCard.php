<?php

/**
 * CreditCard short summary.
 * Credit Card model
 * 
 * CreditCard description.
 * Credit Card model definition
 *
 * @version 1.0
 * @author Raza
 */
include_once("CreditCardResponse.php");
class CreditCard
{
    public $CardholderName = "";
    public $Number = "";
    public $CVV = "";
    public $ExpirationDate = NULL;
    public $ExpirationMonth = "";
    public $ExpirationYear = "";
	public $CardType = "";
	public $IsVerified = "";
    public $Token = "";
    public $Status = "";
    public $CustomerId = "";
	public $Address =null;
    public $IssuerBank= "";
    public $CustomFields =null;
    public $ResponseDetails =null;

    public function setExpirationMonth($ExpirationMonthPassed) {
        $this->ExpirationMonth = $ExpirationMonthPassed;
        if ($this->ExpirationYear != null) {
            if (strlen($this->ExpirationYear) == 4) {
                $this->ExpirationDate = $this->ExpirationMonth + $this->ExpirationYear.substr(2, strlen($this->ExpirationYear));
            } else {
                $this->ExpirationDate = $this->ExpirationMonth + $this->ExpirationYear;
            }
        }
    }

     public function setExpirationYear($ExpirationYearPassed) {
         $this->ExpirationYear = $ExpirationYearPassed;
        if ($this->ExpirationMonth != null) {
            if (strlen($this->ExpirationYear)== 4) {
                $this->ExpirationDate =$this->ExpirationMonth + $this->ExpirationYear.substr(2, strlen($this->ExpirationYear));
            } else {
                $this->ExpirationDate = $this->ExpirationMonth + $this->ExpirationYear;
            }
        }
    }

    function __construct() {
        $this->ResponseDetails = new CreditCardResponse();
    }
    function __destruct() {
        unset($this->ResponseDetails);  
    }
}
