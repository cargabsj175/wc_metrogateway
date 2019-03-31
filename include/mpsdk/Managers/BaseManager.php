<?php

/**
 * BaseManager short summary.
 * Base manager class definition
 *
 * BaseManager description.
 * class handles connectivity to the API.
 * creates request with additionsl global parameters.
 * sends JSON decoded response back to the calling method. 
 *
 * @version 1.0
 * @author Raza
 */
abstract class BaseManager
{    
    protected  $MetropagoObject = null;
    public function __construct(MetropagoGateway $metropagoGateway) {        
        $this->MetropagoObject = new MetropagoGateway($metropagoGateway->Environment, $metropagoGateway->MerchantId, $metropagoGateway->TerminalId, $metropagoGateway->PublicKey, $metropagoGateway->PrivateKey);
    }
    protected function SendAPICurlRequest($model, $requestURL, $method)
    {
        $data = $this->CreateRequestObj($model);
        $data_string = json_encode($data);

        $ch = curl_init($requestURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 100000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100000);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //execute post
        $result = curl_exec($ch);

        curl_close($ch);
        $result = json_decode($result,true);
        return $result ;
    }
    protected function CreateRequestObj($model){
        $RequestData =  array(
            "SDKVersion"=>$this->MetropagoObject->SDKVersion,
            "Identification"=>$this->MetropagoObject->MerchantId,
            "TerminalId"=>$this->MetropagoObject->TerminalId,
            "DateTimeStamp"=> date("d/m/Y",time()),
            "RequestMessage"=>json_encode($model)
        );
        return $RequestData;
    }

    
    protected function ErrorResponse($model , $responseCode,  $msg){        
        $model->IsSuccess  = false;
        $model->ResponseCode = $responseCode;
        $model->ResponseSummary = $msg;
        return $model ;
    }
}

