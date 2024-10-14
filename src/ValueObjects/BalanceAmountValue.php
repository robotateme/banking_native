<?php
declare(strict_types=1);
namespace Banking\ValueObjects;

use Banking\Exceptions\Values\WrongBalanceAmountException;

readonly class BalanceAmountValue
{
    public function __construct(private float $amount)
    {
    }

    /**
     * @throws WrongBalanceAmountException
     */
    public function getValue(): float
    {
        if ($this->amount < 0) {
            throw new WrongBalanceAmountException();
        }
        return $this->amount;
    }
}