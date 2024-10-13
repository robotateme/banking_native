<?php
declare(strict_types=1);
namespace Banking\Entities\Contracts;

use Banking\Entities\CurrencyEntityRate;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;

interface BankEntityInterface
{
    public function getCurrencyRates(): array;

    /**
     * @param  string  $currency
     * @param  string  $currencyRel
     * @param  float  $value
     * @return CurrencyEntityRate
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     */
    public function setNewCurrencyRate(string $currency, string $currencyRel, float $value = 1): CurrencyEntityRateInterface;

    /**
     * @param  string  $currencyFrom
     * @param  string  $currencyTo
     * @param  float  $amount
     * @return float
     * @throws WrongCurrencyRateValueException
     */
    public function exchange(string $currencyFrom, string $currencyTo, float $amount): float;

    /**
     * @return AccountEntityInterface
     */
    public function newAccount(): AccountEntityInterface;
}