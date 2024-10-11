<?php
declare(strict_types=1);
namespace Banking\ValueObjects;

use Banking\Exceptions\Values\WrongBalanceAmountException;

readonly class BalanceAmountValue
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