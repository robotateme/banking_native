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

    public function newAccount(): AccountEntityInterface
    {
        return AccountFactory::create($this);
    }

    /**
     * @param  string  $currencyFrom
     * @param  string  $currencyTo
     * @param  float  $amount
     * @return float
     */
    public function exchange(string $currencyFrom, string $currencyTo, float $amount): float
    {
        /** @var CurrencyRate $rate */
        foreach ($this->currencyRates as $rate) {
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
     * @return CurrencyRate
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     */
    public function setNewCurrencyRate(string $currency, string $currencyRel, float $value = 1): CurrencyRate
    {
        $newRate = CurrencyRateFactory::create($currency, $currencyRel, $value);
        $this->currencyRates[$newRate->getKey()] = $newRate;
        $converseRate = $newRate->makeConverse();
        $this->currencyRates[$converseRate->getKey()] = $newRate->makeConverse();
        return $newRate;
    }

    public function getCurrencyRates(): array
    {
        return $this->currencyRates;
    }
}