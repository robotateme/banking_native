<?php
declare(strict_types=1);
namespace Banking\Entities;

use Banking\Entities\Contracts\CurrencyBalanceInterface;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\ValueObjects\BalanceAmountValue;

class CurrencyBalance implements CurrencyBalanceInterface
{
    /**
     * @param  float  $amount
     * @param  string  $currencyCode
     */
    public function __construct(
        private float $amount,
        private readonly string $currencyCode
    ) {}

    /**
     * @param  float  $value
     * @return void
     * @throws WrongBalanceAmountException
     */
    public function deposit(float $value): void
    {
        $depositValue = new BalanceAmountValue($value);
        $this->amount += $depositValue->getValue();
    }

    /**
     * @param  float  $value
     * @return float
     * @throws WrongBalanceAmountException
     */
    public function withdraw(float $value): float
    {
        $withdrawValue = new BalanceAmountValue($value);
        $this->amount -= $withdrawValue->getValue();
        return $withdrawValue->getValue();
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}