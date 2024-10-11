<?php
declare(strict_types=1);
namespace Banking\Exceptions\Values;

use Banking\Exceptions\Values\Contracts\BaseValueException;

class WrongCurrencyCodeException extends BaseValueException
{
    protected $message = "This currency is not supported by the application";
}