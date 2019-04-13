<?php

/**
 * TransactionManager short summary.
 * Transaction related methods
 *
 * TransactionManager description.
 * class contains all methods related to transaction.
 * methods receives parameters or model and sends calls to the Base Manager.
 * response returned from the Base Manger is returned back to the calling method.
 * Incase of error, error is formatted and returned.
 *
 * @version 1.0
 * @author Raza
  */
 include_once("BaseManager.php");
 include_once MWC_ROOT . "/include/mpsdk/Entities/Transaction.php";
 include_once MWC_ROOT . "/include/mpsdk/Entities/TransactionOptions.php";
 include_once MWC_ROOT . "/include/mpsdk/Entities/TransactionResponse.php";
 class TransactionManager extends BaseManager
{
    public function __construct(MetropagoGateway $metropagoGateway) {        
        parent::__construct($metropagoGateway);
    }

    public function Sale($request){
        if($request->TransactOptions == null){
            $request->TransactOptions = new TransactionOptions();
        }
        $request->TransactOptions->Operation="Sale";
        return $this->PerformOperation($request);
    }

    public function PreAuthorization($request){
        if($request->TransactOptions == null){
            $request->TransactOptions = new TransactionOptions();
        }
        $request->TransactOptions->Operation="PreAuthorization";
        return $this->PerformOperation($request);
    }

    public function Adjustment($request){
        if($request->TransactOptions == null){
            $request->TransactOptions = new TransactionOptions();
        }
        $request->TransactOptions->Operation="Adjustment";
        return $this->PerformOperation($request);
    }

    public function Refund($request){
        if($request->TransactOptions == null){
            $request->TransactOptions = new TransactionOptions();
        }
        $request->TransactOptions->Operation="Refund";
        return $this->PerformOperation($request);
    }
    
    public function InvalidateTransaction($trackingNumber){
        $request = new Transaction();        
        if($request->TransactOptions == null){
            $request->TransactOptions = new TransactionOptions();
        }
        $request->TransactOptions->Operation="Refund";
        $request->OrderTrackingNumber = $trackingNumber;
        return $this->PerformOperation($request);
    }

    public function SearchTransaction($searchFilter){
        $Result = array();
         try{
            $Response = $this->SendAPICurlRequest($searchFilter, $this->MetropagoObject->GatewayURL . "Transaction/SearchTransaction", "POST");     
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

    public function UpdateTransaction($request){
        try{
            $Response = $this->SendAPICurlRequest($request, $this->MetropagoObject->GatewayURL . "Transaction/UpdateTransactionStatus", "POST");
            if($Response == null){
                $request->ResponseDetails = $this->ErrorResponse(new TransactionResponse(),99,"Response object is null");
            }
            else if($Response["ResponseMessage"] == null){
                $request->ResponseDetails = $this->ErrorResponse(new TransactionResponse(),99,"Response message is null");
            }
            else{
                return json_decode($Response["ResponseMessage"]);
            }
        }
        catch(Exception $ex){
            $request->ResponseDetails = $this->ErrorResponse(new TransactionResponse(),99,"Internal server error");
        }
        return $request;
    }


    private function PerformOperation($request){
        try{
            $Response = $this->SendAPICurlRequest($request, $this->MetropagoObject->GatewayURL . "Transaction/PerformTransaction", "POST");     
            if($Response == null){
                $request->ResponseDetails = $this->ErrorResponse(new TransactionResponse(),99,"Response object is null");                
            }
            else if($Response["ResponseMessage"] == null){
                $request->ResponseDetails = $this->ErrorResponse(new TransactionResponse(),99,"Response message is null");                
            }
            else{
                return json_decode($Response["ResponseMessage"]);
            }
        }
        catch(Exception $ex){
            //$cardDetail->ResponseDetails = $this->ErrorResponse(new CreditCardResponse(),99,$ex->getMessage());
            $request->ResponseDetails = $this->ErrorResponse(new TransactionResponse(),99,"Internal server error");
        }
        return $request;
    }
}
