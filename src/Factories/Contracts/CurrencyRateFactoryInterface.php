<?php

namespace Banking\Factories\Contracts;

use Banking\Entities\Contracts\CurrencyRateEntityInterface;

interface CurrencyRateFactoryInterface extends FactoryInterface
{
    public static function create(string $currency, string $currencyRel, float $value): CurrencyRateEntityInterface;
}