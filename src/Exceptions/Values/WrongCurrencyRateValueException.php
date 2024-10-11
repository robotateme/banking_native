<?php
declare(strict_types=1);
namespace Banking\Exceptions\Values;

use Banking\Exceptions\Values\Contracts\BaseValueException;

class WrongCurrencyRateValueException extends BaseValueException
{
    protected $message = "Currency rate value must be greater than 0";
}