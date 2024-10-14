<?php

namespace Banking\ValueObjects;

use Banking\Entities\Contracts\CurrencyBalanceEntityInterface;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\Exceptions\Values\WrongBalanceAmountException;

readonly class BalanceAmountWithdrawValue
{
    public function __construct(private CurrencyBalanceEntityInterface $currencyBalance, private float $value)
    {
    }

    /**
     * @throws BalanceInsufficientFundsException|WrongBalanceAmountException
     */
    public function getValue(): float
    {
        if ($this->value < 0) {
            throw new WrongBalanceAmountException();
        }

        if ($this->currencyBalance->getAmount() < $this->value) {
            throw new BalanceInsufficientFundsException();
        }

        return $this->value;
    }
}