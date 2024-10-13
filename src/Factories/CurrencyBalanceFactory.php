<?php

namespace Banking\Factories;

use Banking\Entities\CurrencyBalance;

class CurrencyBalanceFactory
{
    /**
     * @param  float  $amount
     * @param  string  $currency
     * @return CurrencyBalance
     */
    public static function create(float $amount, string $currency): CurrencyBalance
    {
        return new CurrencyBalance($amount, $currency);
    }
}