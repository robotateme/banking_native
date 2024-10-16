<?php
declare(strict_types=1);

namespace Banking\Entities;

use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Exceptions\Entities\CurrencyBalanceAlreadyExistsException;
use Banking\Exceptions\Entities\DefaultCurrencyIsNotSet;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\BalanceInsufficientFundsException;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Banking\Factories\CurrencyBalanceFactory;
use Banking\Factories\MoneyFactory;
use Banking\ValueObjects\CurrencyCodeValue;

class Account implements AccountEntityInterface
{
    /**
     * @var CurrencyBalance[]
     */
    private array $currencyBalances = [];

    private ?CurrencyCodeValue $defaultCurrency;

    /**
     * @param  Bank  $bank
     */
    public function __construct(private readonly BankEntityInterface $bank)
    {
    }

    /**
     * @param  string  $currencyCode
     * @return void
     * @throws WrongCurrencyCodeException
     * @throws CurrencyBalanceAlreadyExistsException|WrongBalanceAmountException
     */
    public function addCurrencyBalance(string $currencyCode): void
    {
        $currencyCodeValue = new CurrencyCodeValue($currencyCode);
        if (in_array($currencyCodeValue->getValue(), $this->getSupportedCurrencies())) {
            throw new CurrencyBalanceAlreadyExistsException();
        }

        $this->currencyBalances[$currencyCode] = CurrencyBalanceFactory::create(0.00, $currencyCodeValue->getValue());
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
     * @throws UnsupportedCurrencyCode
     */
    public function setDefaultCurrency(string $currencyCode): void
    {
        if (!in_array($currencyCode, $this->getSupportedCurrencies())) {
            throw new UnsupportedCurrencyCode();
        }

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
     * @return float
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     */
    public function deposit(string $currencyCode, float $amount): float
    {
        $balance = $this->currencyBalances[$currencyCode] ?? null;
        if (is_null($balance)) {
            throw new UnsupportedCurrencyCode();
        }

        return $balance->deposit($amount);
    }

    /**
     * @param  string  $currencyCode
     * @param  float  $amount
     * @return Money
     * @throws UnsupportedCurrencyCode
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     * @throws BalanceInsufficientFundsException
     */
    public function withdraw(string $currencyCode, float $amount): Money
    {
        $balance = $this->currencyBalances[$currencyCode];
        if (is_null($balance)) {
            throw new UnsupportedCurrencyCode();
        }

        return MoneyFactory::create($this->bank, $balance->withdraw($amount), $currencyCode);
    }

    /**
     * @param  string|null  $currencyCode
     * @return float
     * @throws DefaultCurrencyIsNotSet
     * @throws WrongCurrencyCodeException|WrongCurrencyRateValueException|UnsupportedCurrencyCode
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
            $summary[] = $this->bank->convert(
                $currencyFrom->getValue(),
                $currencyCode->getValue(),
                $balance->getAmount()
            );
        }
        return array_sum($summary);
    }

    /**
     * @return string|null
     * @throws WrongCurrencyCodeException
     */
    public function getDefaultCurrency(): ?string
    {
        return $this->defaultCurrency->getValue();
    }
}