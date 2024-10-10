<?php

namespace Banking\Entities;

use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Exceptions\Entities\DefaultCurrencyIsNotSet;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Factories\MoneyFactory;
use Banking\Utils\CurrencyBalancesStorage;
use Banking\ValueObjects\BalanceAmountValue;
use Banking\ValueObjects\CurrencyCodeValue;
use SplObjectStorage;

class Account implements AccountEntityInterface
{
    /**
     */
    private SplObjectStorage $currencyBalances;

    private ?CurrencyCodeValue $defaultCurrency;

    /**
     * @param  Bank  $bank
     */
    public function __construct(private readonly Bank $bank)
    {
        $this->currencyBalances = new CurrencyBalancesStorage();
    }

    /**
     * @param  string  $currencyCode
     * @return void
     * @throws WrongCurrencyCodeException
     */
    public function addCurrencyBalance(string $currencyCode): void
    {
        $this->currencyBalances->attach(new CurrencyBalance(0.00, new CurrencyCodeValue($currencyCode)));
    }

    /**
     * @param  CurrencyCodeValue  $currencyCodeValue
     * @return void
     * @throws DefaultCurrencyIsNotSet
     * @throws UnsupportedCurrencyCode|WrongCurrencyCodeException
     * @throws WrongBalanceAmountException
     */
    public function removeCurrencyBalance($currencyCode): void
    {
        if (is_null($this->defaultCurrency)) {
            throw new DefaultCurrencyIsNotSet();
        }

        $currencyBalance = $this->currencyBalances->find($currencyCode);
        $money = MoneyFactory::create($this->bank, $currencyBalance->amount, $currencyCode);
        $this->deposit($this->defaultCurrency, $money->exchangeTo($this->defaultCurrency));
        $this->currencyBalances->detach($currencyBalance);
    }

    /**
     * @param  string  $currencyCode
     * @return void
     * @throws WrongCurrencyCodeException
     */
    public function setDefaultCurrency(string $currencyCode): void
    {
        $this->defaultCurrency = new CurrencyCodeValue($currencyCode);
    }

    /**
     * @return string[]
     */
    public function getSupportedCurrencies(): array
    {
        $currencies = [];
        foreach ($this->currencyBalances as $balance) {
            $currencies[] = $balance->currencyCode;
        }

        return $currencies;
    }

    /**
     * @param  string  $currencyCode
     * @param  float  $balanceAmount
     * @return void
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     */
    public function deposit(string $currencyCode, float $balanceAmount): void
    {
        $balance = $this->currencyBalances->find(new CurrencyCodeValue($currencyCode));
        $balance->deposit($balanceAmount);
    }

    /**
     * @param  CurrencyCodeValue  $currencyCode
     * @param  BalanceAmountValue  $balanceAmount
     * @return Money
     * @throws BalanceInsufficientFundsException
     * @throws UnsupportedCurrencyCode
     * @throws WrongCurrencyCodeException
     */
    public function withdraw(string $currencyCode, float $balanceAmount): Money
    {
        $balance = $this->currencyBalances->find($currencyCode);
        return MoneyFactory::create($this->bank, $balance->withdraw($balanceAmount), $currencyCode);
    }

    /**
     * @param  string|null  $currencyCode
     * @return float
     * @throws DefaultCurrencyIsNotSet
     * @throws WrongCurrencyCodeException
     */
    public function getSummaryBalance(string $currencyCode = null): float
    {
        if (is_null($this->defaultCurrency)) {
            throw new DefaultCurrencyIsNotSet();
        }

        if (is_null($currencyCode)) {
            $currencyCode = $this->defaultCurrency->getValue();
        } else {
            $currencyCode = new CurrencyCodeValue($currencyCode);
        }

        $summary = [];
        foreach ($this->currencyBalances as $balance) {
            $currencyFrom = new CurrencyCodeValue($balance->currencyCode);
            $summary[] = $this->bank->exchange($currencyFrom, $currencyCode, $balance->amount);
        }

        return array_sum($summary);
    }
}