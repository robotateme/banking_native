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
    public SplObjectStorage $currencyBalances;

    private ?Currency $defaultCurrency;

    /**
     * @param  Bank  $bank
     */
    public function __construct(private readonly Bank $bank)
    {
        $this->currencyBalances = new CurrencyBalancesStorage();
    }

    /**
     * @param  CurrencyCodeValue  $currencyCodeValue
     * @return void
     */
    public function addCurrency(CurrencyCodeValue $currencyCodeValue): void
    {
        $this->currencyBalances->attach(new CurrencyBalance(0.00, $currencyCodeValue));
    }

    /**
     * @param  CurrencyCodeValue  $currencyCodeValue
     * @return void
     * @throws DefaultCurrencyIsNotSet
     * @throws UnsupportedCurrencyCode|WrongCurrencyCodeException
     * @throws WrongBalanceAmountException
     */
    public function removeCurrency(CurrencyCodeValue $currencyCodeValue): void
    {
        if (is_null($this->defaultCurrency)) {
            throw new DefaultCurrencyIsNotSet();
        }

        $currencyBalance = $this->currencyBalances->find($currencyCodeValue);
        $defaultCurrencyCode = new CurrencyCodeValue($this->defaultCurrency->code);
        $money = MoneyFactory::create($this->bank, $currencyBalance->amount, $currencyCodeValue);
        $mooneyAmount = new BalanceAmountValue($money->exchangeTo($defaultCurrencyCode));
        $this->deposit($defaultCurrencyCode, $mooneyAmount);

        $this->currencyBalances->detach($currencyBalance);
    }

    /**
     * @param  CurrencyCodeValue  $currencyCode
     * @return void
     */
    public function setDefaultCurrency(CurrencyCodeValue $currencyCode): void
    {
        $this->defaultCurrency = new Currency($currencyCode);
    }

    /**
     * @return Currency[]
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
     * @return Currency
     */
    public function getDefaultCurrency(): Currency
    {
        return $this->defaultCurrency;
    }

    /**
     * @param  CurrencyCodeValue  $currencyCode
     * @param  BalanceAmountValue  $balanceAmount
     * @return void
     * @throws UnsupportedCurrencyCode
     */
    public function deposit(CurrencyCodeValue $currencyCode, BalanceAmountValue $balanceAmount): void
    {
        $balance = $this->currencyBalances->find($currencyCode);
        $balance->deposit($balanceAmount->getValue());
    }

    /**
     * @param  CurrencyCodeValue  $currencyCode
     * @param  BalanceAmountValue  $balanceAmount
     * @return Money
     * @throws BalanceInsufficientFundsException
     * @throws UnsupportedCurrencyCode
     */
    public function withdraw(CurrencyCodeValue $currencyCode, BalanceAmountValue $balanceAmount): Money
    {
        $balance = $this->currencyBalances->find($currencyCode->getValue());
        return MoneyFactory::create($this->bank, $balance->withdraw($balanceAmount->getValue()), $currencyCode);
    }

    /**
     * @param  CurrencyCodeValue|null  $currencyCode
     * @return float
     * @throws WrongCurrencyCodeException|DefaultCurrencyIsNotSet
     */
    public function getSummaryBalance(?CurrencyCodeValue $currencyCode = null): float
    {
        if (is_null($this->defaultCurrency)) {
            throw new DefaultCurrencyIsNotSet();
        }

        if (is_null($currencyCode)) {
            $currencyCode = $this->defaultCurrency->code;
        }

        $summary = [];
        foreach ($this->currencyBalances as $balance) {
            $currencyFrom = new CurrencyCodeValue($balance->currencyCode);
            $summary[$balance->currencyCode . ' => ' . $currencyCode] = $this->bank->exchange($currencyFrom, $currencyCode, $balance->amount);
        }

        return array_sum($summary);
    }
}