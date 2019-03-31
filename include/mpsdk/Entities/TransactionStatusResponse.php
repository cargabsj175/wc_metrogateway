<?php

/**
 * TransactionStatusResponse short summary.
 * Transaction Status Response model
 *
 * TransactionStatusResponse description.
 * Transaction Status Response model definition
 *
 * @version 1.0
 * @author Waqas
 */
include_once("ValidationError.php");
class TransactionStatusResponse
{
    public $ValidationErrors= null;
    public $ResponseSummary= "";
    public $ResponseCode= "";
    public $TransactionId= "";
    public $IsSuccess= false;

    function __construct() {
        $this->ValidationErrors  = new ValidationError();
    }
    function __destruct() {
        unset($this->ValidationErrors);
    }
}