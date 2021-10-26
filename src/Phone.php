<?php

class Phone
{
    public $phone;
    public $operator;

    public function __construct($phone, $operatorID)
    {
        $this->phone = $phone;
        $this->operator = Operator::getOperatorByID($operatorID);
    }



}