<?php

namespace Banking\Exceptions\Values;

use Banking\Exceptions\Values\Contracts\BaseValueException;

class BalanceInsufficientFundsException extends BaseValueException
{
    protected $message = "Insufficient funds.";
}