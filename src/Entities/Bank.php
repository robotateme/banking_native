<?php
namespace Banking\Entities;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Factories\AccountFactory;

class Bank implements BankEntityInterface
{
    /**
     * @param CurrencyRate[]  $rates
     */
    public function __construct(private array $rates)
    {

    }

    public function newAccount(): Account
    {
        return AccountFactory::create($this);
    }

    public function getRates(): array
    {
        return $this->rates;
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
        foreach ($this->rates as $rate) {
            $convRates[] = new CurrencyRate($rate->currencyRel, $rate->currencyCode,  1 / $rate->value);
        }
        $rates = array_merge($this->rates, $convRates);

        /** @var CurrencyRate $rate */
        foreach ($rates as $rate) {
            if ($currencyFrom === $rate->currencyCode && $currencyTo === $rate->currencyRel) {
                return $amount * $rate->value;
            }
        }

        return $amount;
    }

    public function setNewRateValue(CurrencyRate $newRate): array
    {
        foreach ($this->rates as $rate) {
            if ($rate->currencyCode === $newRate->currencyCode) {
                $rate->value = $newRate->value;
                return $this->rates;
            }
        }

        $this->rates[] = $newRate;
        return $this->rates;
    }
}