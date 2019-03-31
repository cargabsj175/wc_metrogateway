<?php

/**
 * CustomerEntityResponse short summary.
 * Customer Entity Response model
 *
 * CustomerEntityResponse description.
 * Customer Entity Response model definition
 *
 * @version 1.0
 * @author Waqas
 */
include_once("ValidationError.php");
class CustomerEntityResponse
{
    public $IsSuccess= "";
    public $ResponseSummary= "";
    public $ResponseCode= "";
    public $Id= null;
    public $AccountToken= "";
    public $ValidationErrors= "";

    function __construct() {
        $this->ValidationErrors  = new ValidationError();
    }
    function __destruct() {
        unset($this->ValidationErrors);
    }
}