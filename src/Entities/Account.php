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
use Banking\ValueObjects\AccountDepositValues;
use Banking\ValueObjects\AccountWithdrawValues;
use Banking\ValueObjects\CurrencyCodeValue;

class Account implements AccountEntityInterface
{
    /**
     * @var CurrencyEntityBalance[]
     */
    private array $currencyBalances = [];

    private ?CurrencyCodeValue $defaultCurrency;

    /**
     * @param  Bank  $bank
     */
    public function __construct(private readonly Bank $bank)
    {
    }

    /**
     * @param  string  $currencyCode
     * @return void
     * @throws WrongCurrencyCodeException
     * @throws CurrencyBalanceAlreadyExistsException
     */
    public function addCurrencyBalance(string $currencyCode): void
    {
        $currencyCodeValue = new CurrencyCodeValue($currencyCode);
        if (in_array($currencyCodeValue->getValue(), $this->getSupportedCurrencies())) {
            throw new CurrencyBalanceAlreadyExistsException();
        }

        $this->currencyBalances[$currencyCode] = new CurrencyEntityBalance(0.00, $currencyCodeValue->getValue());
    }

    /**
     * @param  string  $currencyCode
     * @return void
     * @throws DefaultCurrencyIsNotSet
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException|WrongCurrencyRateValueException
     */
    public function removeCurrencyBalance(string $currencyCode): void
    {
        if (is_null($this->defaultCurrency)) {
            throw new DefaultCurrencyIsNotSet();
        }

        $currencyCodeValue = new CurrencyCodeValue($currencyCode);
        $currencyBalance = $this->currencyBalances[$currencyCodeValue->getValue()] ?? null;

        if (is_null($currencyBalance)) {
            throw new UnsupportedCurrencyCode();
        }

        $money = MoneyFactory::create($this->bank, $currencyBalance->getAmount(), $currencyCode);
        $moneyDefault = $money->exchangeTo($this->defaultCurrency->getValue());
        $this->deposit($this->defaultCurrency->getValue(), $moneyDefault->getAmount());
        unset($this->currencyBalances[$currencyCodeValue->getValue()]);
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
     * @param  float  $amount
     * @return void
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     */
    public function deposit(string $currencyCode, float $amount): void
    {
        $values = new AccountDepositValues($currencyCode, $amount, $this->getSupportedCurrencies());
        $balance = $this->currencyBalances[$values->getCurrencyCode()];
        $balance->deposit($values->getAmount());
    }

    /**
     * @param  string  $currencyCode
     * @param  float  $amount
     * @return MoneyEntity
     * @throws BalanceInsufficientFundsException
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     */
    public function withdraw(string $currencyCode, float $amount): MoneyEntity
    {
        $balance = $this->currencyBalances[$currencyCode];

        if (is_null($balance)) {
            throw new UnsupportedCurrencyCode();
        }

        $values = new AccountWithdrawValues(
            $currencyCode,
            $amount,
            $this->getSupportedCurrencies(),
            $balance->getAmount()
        );

        return MoneyFactory::create($this->bank, $balance->withdraw($amount), $values->getCurrencyCode());
    }

    /**
     * @param  string|null  $currencyCode
     * @return float
     * @throws DefaultCurrencyIsNotSet
     * @throws WrongCurrencyCodeException|WrongCurrencyRateValueException
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