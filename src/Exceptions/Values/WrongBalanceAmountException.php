<?php
declare(strict_types=1);
namespace Banking\Exceptions\Values;


use Banking\Exceptions\Values\Contracts\BaseValueException;

class WrongBalanceAmountException extends BaseValueException
{
    protected $message = "Amount must be greater than or equal to zero";
}