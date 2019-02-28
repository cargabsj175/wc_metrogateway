<?php

/**
 * ParameterFilter short summary.
 * Parameter Filter model
 *
 * ParameterFilter description.
 * File contains following three type of filter classes
 * -AmountRangeFilter
 * -DateRangeFilter
 * -TextFilter
 * 
 * All filter classes contains helping methods for the class operations.
 *
 * @version 1.0
 * @author Raza
 */
class AmountRangeFilter{
    public $Amount1 = 0;
    public $Amount2 = 0;
    public $Operation = "";

    public function ClearFilter()
    {
        $this->Amount1= 0;
        $this->Amount2 = 0;
        $this->Operation = "";
    }
    public function GreaterThan($amount)
    {
        $this->ClearFilter();
        $this->Amount1= $amount;
        $this->Operation = "GREATER_THAN";
    }
    public function LessThan($amount)
    {
        $this->ClearFilter();
        $this->Amount1= $amount;
        $this->Operation = "LESS_THAN";
    }
    public function EqualTo($amount)
    {
        $this->ClearFilter();
        $this->Amount1= $amount;
        $this->Operation = "EQUAL_TO";
    }
    public function BETWEEN($amountFrom, $amountTo)
    {
        $this->ClearFilter();
        $this->Amount1= $amountFrom;
        $this->Amount2= $amountTo;
        $this->Operation = "BETWEEN";
    }
 }

class DateRangeFilter{
    public $Date1 = null;
    public $Date2 = null;
    public $Operation = "";

    public function ClearFilter()
    {
        $this->Date1= null;
        $this->Date2 = null;
        $this->Operation = "";
    }
    public function GreaterThan($date)
    {
        $this->ClearFilter();
        $this->Date1= $date;
        $this->Operation = "GREATER_THAN";
    }
    public function LessThan($date)
    {
        $this->ClearFilter();
        $this->Date1= $date;
        $this->Operation = "LESS_THAN";
    }
    public function EqualTo($date)
    {
        $this->ClearFilter();
        $this->Date1= $date;
        $this->Operation = "EQUAL_TO";
    }
    public function BETWEEN($dateFrom, $dateTo)
    {
        $this->ClearFilter();
        $this->Date1= $dateFrom;
        $this->Date2= $dateTo;
        $this->Operation = "BETWEEN";
    }
}

class TextFilter{
    public $Text = "";
    public $Operation = "";

    public function ClearFilter()
    {
        $this->Text= "";
        $this->Operation = "";
    }
    public function StartsWith($text)
    {
        $this->ClearFilter();
        $this->Text= $text;
        $this->Operation = "STARTS_WITH";
    }
    public function EndsWith($text)
    {
        $this->ClearFilter();
        $this->Text= $text;
        $this->Operation = "ENDS_WITH";
    }
    public function Is($text)
    {
        $this->ClearFilter();
        $this->Text= $text;
        $this->Operation = "IS";
    }
}
?>