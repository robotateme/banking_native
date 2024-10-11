<?php

namespace Banking\ValueObjects;

use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;

readonly class AccountDepositValues
{
    /**
     * @throws UnsupportedCurrencyCode
     */
    public function __construct(
        private string $currencyCode,
        private float $amount,
        private array $supportedCurrencies,
    )
    {
        if (!in_array($currencyCode, $this->supportedCurrencies)) {
            throw new UnsupportedCurrencyCode();
        }
    }

    /**
     * @throws WrongCurrencyCodeException
     */
    public function getCurrencyCode(): string
    {
        return (new CurrencyCodeValue($this->currencyCode));
    }

    /**
     * @throws WrongBalanceAmountException
     */
    public function getAmount(): float
    {
        return (new BalanceAmountValue($this->amount))->getValue();
    }

    public function getValue()
    {
        // TODO: Implement getValue() method.
    }
}