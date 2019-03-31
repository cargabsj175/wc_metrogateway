<?php

/**
 * MetropagoGateway short summary.
 * Configuration file
 *
 * MetropagoGateway description.
 * class contains global variables and API URL.
 * these are used in Base Manager class.
 *
 * @version 1.0
 * @author Raza
 */
class MetropagoGateway
{
    public $SDKVersion = "1.1";
    public $Environment ="";
    public $GatewayURL="";
    public $MerchantId = "";
    public $TerminalId = "";
    public $PublicKey = "";
    public $PrivateKey = "";

    function __construct($environment, $merchantId, $terminalId , $publicKey, $privateKey) {
        $this->Environment = $environment;
        $this->MerchantId = $merchantId;
        $this->TerminalId = $terminalId;
        $this->PublicKey = $publicKey;
        $this->PrivateKey= $privateKey;
        switch ($environment)
            {
                case "SANDBOX":
                    $this->GatewayURL = "http://securegateway.merchantprocess.net/NeogatewayApi_Test/api/";
                    break;
                case "PRODUCTION":
                    $this->GatewayURL = "https://gateway.merchantprocess.net/api/prod-v1.0/api/";
                    break;
                default:
                    throw new Exception("Invalid Enviroment");
            }
    }
}
