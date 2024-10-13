<?php

namespace Banking\Factories;

use Banking\Entities\CurrencyBalance;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\ValueObjects\BalanceAmountValue;
use Banking\ValueObjects\CurrencyCodeValue;

class CurrencyBalanceFactory
{
    /**
     * @param  float  $amount
     * @param  string  $currency
     * @return CurrencyBalance
     * @throws WrongCurrencyCodeException
     * @throws WrongBalanceAmountException
     */
    public static function create(float $amount, string $currency): CurrencyBalance
    {
        return new CurrencyBalance(
            (new BalanceAmountValue($amount))->getValue(),
            (new CurrencyCodeValue($currency))->getValue()
        );
    }
}