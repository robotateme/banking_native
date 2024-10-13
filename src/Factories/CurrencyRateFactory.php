<?php

namespace Banking\Factories;

use Banking\Entities\CurrencyEntityRate;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Banking\Factories\Contracts\FactoryInterface;
use Banking\ValueObjects\CurrencyCodeValue;

class CurrencyRateFactory implements FactoryInterface
{
    /**
     * @throws WrongCurrencyRateValueException
     * @throws WrongCurrencyCodeException
     */
    public static function create(string $currency, string $currencyRel, float $value): CurrencyEntityRate
    {
        return new CurrencyEntityRate(
            (new CurrencyCodeValue($currency))->getValue(),
            (new CurrencyCodeValue($currencyRel))->getValue(),
            $value);
    }
}