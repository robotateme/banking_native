<?php

namespace Banking\Factories;

use Banking\Entities\CurrencyRate;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Banking\Factories\Contracts\FactoryInterface;
use Banking\ValueObjects\CurrencyCodeValue;
use Banking\ValueObjects\CurrencyRateValue;

class CurrencyRateFactory implements FactoryInterface
{
    /**
     * @throws WrongCurrencyRateValueException
     * @throws WrongCurrencyCodeException
     */
    public static function create(string $currency, string $currencyRel, float $value): CurrencyRate
    {
        return new CurrencyRate(
            (new CurrencyCodeValue($currency))->getValue(),
            (new CurrencyCodeValue($currencyRel))->getValue(),
            (new CurrencyRateValue($value))->getValue()
        );
    }
}