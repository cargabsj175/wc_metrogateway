<?php

/**
 * CustomerSearch short summary.
 * Customer Search model
 *
 * CustomerSearch description.
 * Customer Search model definition
 *
 * @version 1.0
 * @author Raza
 */
include_once("ParameterFilter.php");
include_once("CustomerSearchOption.php");
class CustomerSearch
{
    public $CustomerId = "";
    public $UniqueIdentifier = "";
    public $CardToken = "";
    public $Merchant = "";
    public $Email = null;
    public $Fax = null;
    public $FirstName = null;
    public $LastName = null;
    public $Phone = null;
    public $Website = null;
    public $Company = null;
    public $DateCreated = null;
    public $CardNumber = null;
    public $CardHolderName = null;
    public $SearchOption = null;
    
    function __construct() {        
        $this->Email  = new TextFilter();
        $this->Fax  = new TextFilter();
        $this->FirstName   = new TextFilter();
        $this->LastName  = new TextFilter();
        $this->Phone  = new TextFilter();
        $this->Website  = new TextFilter();
        $this->Company  = new TextFilter();
        $this->DateCreated  = new DateRangeFilter();
        $this->CardNumber  = new TextFilter();
        $this->CardHolderName  = new TextFilter();
        $this->SearchOption  = new CustomerSearchOption();
    }

    function __destruct() {
        unset($this->Email);        
        unset($this->Fax);        
        unset($this->FirstName);        
        unset($this->LastName);        
        unset($this->Phone);        
        unset($this->Website);      
        unset($this->Company);      
        unset($this->DateCreated);      
        unset($this->CardNumber);      
        unset($this->CardHolderName);
        unset($this->SearchOption);
    }
}
