<?php

namespace Banking\ValueObjects;

use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;

readonly class AccountDepositValues
{
    public function __construct(
        private string $currencyCode,
        private float $amount,
    )
    {}

    /**
     * @return string
     * @throws WrongCurrencyCodeException
     */
    public function getCurrencyCode(): string
    {


        return (new CurrencyCodeValue($this->currencyCode))->getValue();
    }

    /**
     * @throws WrongBalanceAmountException
     */
    public function getAmount(): float
    {
        return (new BalanceAmountValue($this->amount))->getValue();
    }
}