<?php

namespace Banking\Utils;

use Banking\Entities\CurrencyBalance;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\ValueObjects\CurrencyCodeValue;
use SplObjectStorage;
class CurrencyBalancesStorage extends SplObjectStorage
{
    /**
     * @throws UnsupportedCurrencyCode
     * @throws WrongCurrencyCodeException
     */
    public function find(string $currencyCode): ?CurrencyBalance
    {
        $currencyCode = new CurrencyCodeValue($currencyCode);
        foreach ($this as $balance) {
            if ($balance->currencyCode === $currencyCode->getValue()) {
                return $balance;
            }
        }
        throw new UnsupportedCurrencyCode();
    }
}