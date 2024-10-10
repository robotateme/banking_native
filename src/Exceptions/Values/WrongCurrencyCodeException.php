<?php

namespace Banking\Exceptions\Values;

use Banking\Exceptions\Values\Contracts\BaseValueException;

class WrongCurrencyCodeException extends BaseValueException
{
    const string Message = "Wrong currency code %s";
    public function __construct(string $currencyCode)
    {
        parent::__construct(sprintf(self::Message, $currencyCode));
    }
}