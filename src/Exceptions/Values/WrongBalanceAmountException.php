<?php

namespace Banking\Exceptions\Values;


use Banking\Exceptions\Values\Contracts\BaseValueException;
use Throwable;

class WrongBalanceAmountException extends BaseValueException
{
    protected $message = "Amount must be greater than or equal to zero";
}