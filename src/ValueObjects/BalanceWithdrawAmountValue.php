<?php

namespace Banking\ValueObjects;

use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\ValueObjects\Contracts\ValueObjectInterface;

readonly class BalanceWithdrawAmountValue implements ValueObjectInterface
{
    private float $value;
    /**
     * @param  float  $amount
     * @param  float  $subAmount
     * @throws BalanceInsufficientFundsException
     */
    public function __construct(private float $amount, private float $subAmount)
    {
        if (($this->amount - $this->subAmount) < 0) {
            throw new BalanceInsufficientFundsException();
        }

        $this->value = $this->amount - $this->subAmount;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}