<?php

namespace Banking\ValueObjects;

use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\ValueObjects\Contracts\ValueObjectInterface;

readonly class BalanceAmountValue implements ValueObjectInterface
{
    /**
     * @throws WrongBalanceAmountException
     */
    public function __construct(private float $amount)
    {
        if ($amount < 0) {
            throw new WrongBalanceAmountException();
        }
    }

    public function getValue(): float
    {
        return $this->amount;
    }
}