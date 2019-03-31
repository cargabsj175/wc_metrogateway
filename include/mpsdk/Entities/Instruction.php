<?php

/**
 * Instruction short summary.
 * Instruction model
 *
 * Instruction description.
 * Instruction model definition
 *
 * @version 1.0
 * @author Waqas
 */
include_once("InstructionResponse.php");
class Instruction
{
    public $Id= "";
    public $CustomerId= "";
    public $CustomerEntityId= "";
    public $InstrumentToken= "";
    public $Status= "";
    public $ScheduleDay= "";
    public $CustomFields= null;
    private $Response;
    public $ExpirationDate= "";
    public $AccountToken= "";
    public $CustomerEntityValue= "";
    public $AccountNumber= "";

    function __construct() {
        $this->Response  = new InstructionResponse();
    }
    function __destruct() {
        unset($this->Response);
    }
}