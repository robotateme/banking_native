<?php
declare(strict_types=1);
namespace Banking\Exceptions\Values;

use Banking\Exceptions\Values\Contracts\BaseValueException;

class BalanceInsufficientFundsException extends BaseValueException
{
    protected $message = "Insufficient funds.";
}