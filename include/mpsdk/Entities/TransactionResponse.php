<?php

/**
 * TransactionResponse short summary.
 * Transaction response model
 *
 * TransactionResponse description.
 * Transaction response model definition
 *
 * @version 1.0
 * @author Raza
 */
include_once("ValidationError.php");
class TransactionResponse
{
    public $ValidationErrors =null;
    public $IsSuccess = false;
    public $ResponseSummary = "";
    public $AuthorizationNumber = "";
    public $ResponseCode = "";
    public $TransactionId = "";
    function __construct() {
        $this->ValidationErrors  = new ValidationError();
    }    
    function __destruct() {
        unset($this->ValidationErrors);      
    }
}
