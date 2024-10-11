<?php
declare(strict_types=1);
namespace Banking\ValueObjects;

use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;

readonly class AccountWithdrawValues extends AccountDepositValues
{
    /**
     * @param  string  $currencyCode
     * @param  float  $amount
     * @param  array  $supportedCurrencies
     * @param  float  $balanceAmount
     * @throws BalanceInsufficientFundsException
     * @throws UnsupportedCurrencyCode
     */
    public function __construct(
        private string $currencyCode,
        private float $amount,
        private array $supportedCurrencies,
        private float $balanceAmount,
    )
    {
        if (($this->balanceAmount - $this->amount) < 0) {
            throw new BalanceInsufficientFundsException();
        }

        parent::__construct($this->currencyCode, $this->amount, $this->supportedCurrencies);

    }
}