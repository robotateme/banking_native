<?php

namespace Banking\Entities;

use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\Contracts\MoneyInterface;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\ValueObjects\CurrencyCodeValue;

readonly class Money implements MoneyInterface
{
    public function __construct(
        private BankEntityInterface $bank,
        private float $amount,
        private string $currencyCode
    ) {

    }

    /**
     * @param  string  $currencyCodeTo
     * @return float
     * @throws WrongCurrencyCodeException
     */
    public function exchangeTo(string $currencyCodeTo): float
    {
        return $this->bank->exchange($this->currencyCode, new CurrencyCodeValue($currencyCodeTo), $this->amount);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}