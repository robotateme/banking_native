<?php
declare(strict_types=1);

namespace Banking\Entities;

use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Exceptions\Entities\CurrencyBalanceAlreadyExistsException;
use Banking\Exceptions\Entities\DefaultCurrencyIsNotSet;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Banking\Factories\MoneyFactory;
use Banking\Utils\CurrencyBalancesStorage;
use Banking\ValueObjects\AccountDepositValues;
use Banking\ValueObjects\AccountWithdrawValues;
use Banking\ValueObjects\CurrencyCodeValue;

class Account implements AccountEntityInterface
{
    /**
     * @var CurrencyBalance[]|CurrencyBalancesStorage
     */
    private CurrencyBalancesStorage|array $currencyBalances;

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
     * @throws CurrencyBalanceAlreadyExistsException
     */
    public function addCurrencyBalance(string $currencyCode): void
    {
        $currencyCode = new CurrencyCodeValue($currencyCode);
        if (in_array($currencyCode->getValue(), $this->getSupportedCurrencies())) {
            throw new CurrencyBalanceAlreadyExistsException();
        }

        $this->currencyBalances->attach(new CurrencyBalance(0.00, $currencyCode->getValue()));
    }

    /**
     * @param  CurrencyCodeValue  $currencyCodeValue
     * @return void
     * @throws DefaultCurrencyIsNotSet
     * @throws UnsupportedCurrencyCode|WrongCurrencyCodeException
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyRateValueException
     */
    public function removeCurrencyBalance(string $currencyCode): void
    {
        if (is_null($this->defaultCurrency)) {
            throw new DefaultCurrencyIsNotSet();
        }

        $currencyCodeValue = new CurrencyCodeValue($currencyCode);
        $currencyBalance = $this->currencyBalances->find($currencyCodeValue->getValue());

        $money = MoneyFactory::create($this->bank, $currencyBalance->getAmount(), $currencyCode);
        $this->deposit($this->defaultCurrency->getValue(), $money->exchangeTo($this->defaultCurrency->getValue()));
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
            $currencies[] = $balance->getCurrencyCode();
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
    public function deposit(string $currencyCode, float $amount): void
    {
        $values = new AccountDepositValues($currencyCode, $amount, $this->getSupportedCurrencies());
        $balance = $this->currencyBalances->find($values->getCurrencyCode());
        $balance->deposit($values->getAmount());
    }

    /**
     * @param  string  $currencyCode
     * @param  float  $amount
     * @return Money
     * @throws BalanceInsufficientFundsException
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     */
    public function withdraw(string $currencyCode, float $amount): Money
    {
        $balance = $this->currencyBalances->find($currencyCode);
        $values = new AccountWithdrawValues(
            $currencyCode,
            $amount,
            $this->getSupportedCurrencies(),
            $balance->getAmount()
        );

        return MoneyFactory::create($this->bank, $balance->withdraw($values->getAmount()), $values->getCurrencyCode());
    }

    /**
     * @param  string|null  $currencyCode
     * @return float
     * @throws DefaultCurrencyIsNotSet
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     */
    public function getSummaryBalance(string $currencyCode = null): float
    {
        if (is_null($this->defaultCurrency)) {
            throw new DefaultCurrencyIsNotSet();
        }

        if (is_null($currencyCode)) {
            $currencyCode = $this->defaultCurrency;
        } else {
            $currencyCode = new CurrencyCodeValue($currencyCode);
        }

        $summary = [];
        foreach ($this->currencyBalances as $balance) {
            $currencyFrom = new CurrencyCodeValue($balance->getCurrencyCode());
            $summary[] = $this->bank->exchange(
                $currencyFrom->getValue(),
                $currencyCode->getValue(),
                $balance->getAmount()
            );
        }

        return array_sum($summary);
    }
}