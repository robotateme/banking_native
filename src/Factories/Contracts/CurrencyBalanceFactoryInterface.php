<?php

namespace Banking\Factories\Contracts;

use Banking\Entities\Contracts\CurrencyBalanceEntityInterface;
use Banking\Entities\CurrencyBalance;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;

interface CurrencyBalanceFactoryInterface extends FactoryInterface
{
    /**
     * @param  float  $amount
     * @param  string  $currency
     * @return CurrencyBalance
     * @throws WrongCurrencyCodeException
     * @throws WrongBalanceAmountException
     */
    public static function create(float $amount, string $currency): CurrencyBalanceEntityInterface;
}