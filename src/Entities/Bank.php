<?php
declare(strict_types=1);

namespace Banking\Entities;

use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Banking\Factories\AccountFactory;
use Banking\Factories\CurrencyRateFactory;
use Banking\Utils\CurrencyRatesStorage;

class Bank implements BankEntityInterface
{
    private CurrencyRatesStorage $currencyRates;

    /**
     * @param  CurrencyRate[]  $currencyRates
     */
    public function __construct(array $currencyRates = [])
    {
        $this->currencyRates = new CurrencyRatesStorage();

    }

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
        foreach ($this->currencyRates as $rate) {
            $convRate = new CurrencyRate($rate->getCurrencyRel(), $rate->getCurrencyCode(), 1 / $rate->getValue());
            $this->currencyRates->attach($convRate);
        }

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
        foreach ($this->currencyRates as $rate) {
            if ($rate->getCurrencyCode() === $currency && $rate->getCurrencyRel() === $currencyRel) {
                $rate->setValue($value);
                return $rate;
            }
        }

        $this->currencyRates->attach($newRate);
        return $newRate;
    }

    public function getCurrencyRates(): CurrencyRatesStorage
    {
        return $this->currencyRates;
    }
}