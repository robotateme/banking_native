<?php

namespace Banking\Entities;

use Banking\Entities\Contracts\BankEntityInterface;
use Banking\ValueObjects\CurrencyCodeValue;

readonly class Money
{
    public function __construct(
        private BankEntityInterface $bank,
        private float $amount,
        private string $currencyCode
    ) {

    }

    public function exchangeTo(CurrencyCodeValue $currencyCodeTo): float
    {
        return $this->bank->exchange($this->currencyCode, $currencyCodeTo, $this->amount);
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