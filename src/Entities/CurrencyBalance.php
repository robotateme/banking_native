<?php
namespace Banking\Entities;

use Banking\Entities\Contracts\CurrencyBalanceInterface;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\ValueObjects\BalanceAmountValue;
use Banking\ValueObjects\BalanceWithdrawAmountValue;

class CurrencyBalance implements CurrencyBalanceInterface
{
    /**
     * @param  float  $amount
     * @param  string  $currencyCode
     */
    public function __construct(
        public float $amount,
        public string $currencyCode
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
     * @throws BalanceInsufficientFundsException
     */
    public function withdraw(float $value): float
    {
        $newAmount = new BalanceWithdrawAmountValue($this->amount, $value);
        $this->amount = $newAmount->getValue();
        return $value;
    }
}