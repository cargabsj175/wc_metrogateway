<?php

/**
 * CustomerSearchOption short summary.
 * Customer Search Option model
 *
 * CustomerSearchOption description.
 * Customer Search Option model definition
 *
 * @version 1.0
 * @author Waqas
 */
class CustomerSearchOption
{
    public $IncludeAll=false;
    public $IncludeCardInstruments=false;
    public $IncludeACHInstruments=false;
    public $IncludeWallet=false;
    public $IncludeAssociatedEntities=false;
    public $IncludeBillingAddress=false;
    public $IncludeShippingAddress=false;
    public $IncludeCustomFields=false;
    public $UpdateCustomerEntityBalance=false;
    public $IncludePaymentInstructions=false;
}