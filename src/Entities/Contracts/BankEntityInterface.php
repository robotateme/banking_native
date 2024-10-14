<?php
declare(strict_types=1);
namespace Banking\Entities\Contracts;

use Banking\Entities\CurrencyRate;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;

interface BankEntityInterface extends EntityInterface
{
    public function getCurrencyRates(): array;

    /**
     * @param  string  $currency
     * @param  string  $currencyRel
     * @param  float  $value
     * @return CurrencyRate
     * @throws WrongCurrencyCodeException
     * @throws WrongCurrencyRateValueException
     */
    public function setNewCurrencyRate(string $currency, string $currencyRel, float $value = 1): CurrencyRateEntityInterface;

    /**
     * @param  string  $currencyFrom
     * @param  string  $currencyTo
     * @param  float  $amount
     * @return float
     * @throws WrongCurrencyRateValueException
     */
    public function convert(string $currencyFrom, string $currencyTo, float $amount): float;

    /**
     * @return AccountEntityInterface
     */
    public function newAccount(): AccountEntityInterface;
}