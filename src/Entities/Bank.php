<?php
declare(strict_types=1);

namespace Banking\Entities;

use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Banking\Factories\AccountFactory;
use Banking\Factories\CurrencyRateFactory;

class Bank implements BankEntityInterface
{
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
     */
    public function exchange(string $currencyFrom, string $currencyTo, float $amount): float
    {
        /** @var CurrencyEntityRate $rate */
        foreach ($this->currencyRates as $rate) {
            $converseRate = $rate->makeRateConverse();
            $this->currencyRates[$converseRate->getKey()] = $rate->makeRateConverse();
            if ($currencyFrom === $rate->getCurrencyCode() && $currencyTo === $rate->getCurrencyRel()) {
                return $amount * $rate->getValue();
            }
        }

        return $amount;
    }

    /**
     * @param  string  $currency
     * @param  string  $currencyRel
     * @param  float  $value
     * @return CurrencyEntityRate
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     */
    public function setNewCurrencyRate(string $currency, string $currencyRel, float $value = 1): CurrencyEntityRate
    {
        $newRate = CurrencyRateFactory::create($currency, $currencyRel, $value);
        $this->currencyRates[$newRate->getKey()] = $newRate;
        $converseRate = $newRate->makeRateConverse();
        $this->currencyRates[$converseRate->getKey()] = $newRate->makeRateConverse();
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