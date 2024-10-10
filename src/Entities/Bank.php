<?php

namespace Banking\Entities;

use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Factories\AccountFactory;
use Banking\ValueObjects\CurrencyCodeValue;

class Bank implements BankEntityInterface
{
    /**
     * @param  CurrencyRate[]  $currencyRates
     */
    public function __construct(private array $currencyRates = [])
    {

    }

    public function newAccount(): Account
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
        $convRates = [];
        foreach ($this->currencyRates as $rate) {
            $convRates[] = new CurrencyRate($rate->getCurrencyRel(), $rate->getCurrencyCode(), 1 / $rate->getValue());
        }

        $rates = array_merge($this->currencyRates, $convRates);

        /** @var CurrencyRate $rate */
        foreach ($rates as $rate) {
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
     */
    public function setNewCurrencyRate(string $currency, string $currencyRel, float $value = 1): CurrencyRate
    {
        $newRate = new CurrencyRate(new CurrencyCodeValue($currency), new CurrencyCodeValue($currencyRel), $value);
        foreach ($this->currencyRates as $rate) {
            if ($rate->getCurrencyCode() === $currency && $rate->getCurrencyRel() === $currencyRel) {
                $rate->setValue($value);
                return $rate;
            }
        }

        $this->currencyRates[] = $newRate;
        return $newRate;
    }
}