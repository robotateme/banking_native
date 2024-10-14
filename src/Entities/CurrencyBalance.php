<?php
declare(strict_types=1);
namespace Banking\Entities;

use Banking\Entities\Contracts\CurrencyBalanceEntityInterface;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\ValueObjects\BalanceAmountValue;
use Banking\ValueObjects\BalanceAmountWithdrawValue;

class CurrencyBalance implements CurrencyBalanceEntityInterface
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
     * @return float
     * @throws WrongBalanceAmountException
     */
    public function deposit(float $value): float
    {
        $depositValue = new BalanceAmountValue($value);
        $this->amount += $depositValue->getValue();
        return $value;
    }

    /**
     * @param  float  $value
     * @return float
     * @throws WrongBalanceAmountException
     * @throws BalanceInsufficientFundsException
     */
    public function withdraw(float $value): float
    {
        $withdrawValue = new BalanceAmountWithdrawValue($this, $value);
        $this->amount = $this->amount  - $withdrawValue->getValue();
        return $value;
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