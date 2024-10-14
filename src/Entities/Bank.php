<?php
declare(strict_types=1);

namespace Banking\Entities;

use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\Contracts\CurrencyRateEntityInterface;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Banking\Factories\AccountFactory;
use Banking\Factories\CurrencyRateFactory;

class Bank implements BankEntityInterface
{
    /**
     * @var CurrencyRateEntityInterface[]
     */
    private array $currencyRates = [];

    /**
     * @return AccountEntityInterface
     */
    public function newAccount(): AccountEntityInterface
    {
        return AccountFactory::create($this);
    }

    /**
     * @param  string  $currencyFrom
     * @param  string  $currencyTo
     * @param  float  $amount
     * @return float
     * @throws WrongCurrencyRateValueException
     * @throws UnsupportedCurrencyCode
     */
    public function convert(string $currencyFrom, string $currencyTo, float $amount): float
    {

        if ($currencyFrom === $currencyTo) {
            return $amount;
        }

        $currencyRates = $this->currencyRates;
        /** @var CurrencyRate $rate */
        foreach ($this->currencyRates as $rate) {
            $convRate = $rate->makeRateConverse();
            $currencyRates[$convRate->getKey()] = $convRate;
        }

        foreach ($currencyRates as $rate) {
            if ($currencyFrom === $rate->getCurrencyCode() && $currencyTo === $rate->getCurrencyRel()) {
                return $amount * $rate->getValue();
            }
        }

        throw new UnsupportedCurrencyCode();
    }

    /**
     * @param  string  $currency
     * @param  string  $currencyRel
     * @param  float  $value
     * @return CurrencyRate
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     */
    public function setNewCurrencyRate(string $currency, string $currencyRel, float $value = 1): CurrencyRate
    {
        $newRate = CurrencyRateFactory::create($currency, $currencyRel, $value);
        $this->currencyRates[$newRate->getKey()] = $newRate;
        return $newRate;
    }

    /**
     * @return array
     */
    public function getCurrencyRates(): array
    {
        return $this->currencyRates;
    }
}