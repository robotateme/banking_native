<?php

namespace Banking\Utils;

use Banking\Entities\CurrencyBalance;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use SplObjectStorage;
class CurrencyBalancesStorage extends SplObjectStorage
{
    /**
     * @throws UnsupportedCurrencyCode
     */
    public function find(string $currencyCode): ?CurrencyBalance
    {
        foreach ($this as $balance) {
            if ($balance->currencyCode === $currencyCode) {
                return $balance;
            }
        }
        throw new UnsupportedCurrencyCode();
    }
}