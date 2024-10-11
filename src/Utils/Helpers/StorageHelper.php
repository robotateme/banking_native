<?php

namespace Banking\Utils\Helpers;

use Banking\Entities\Contracts\CurrencyBalanceInterface;
use Banking\Entities\Contracts\CurrencyRateInterface;

class StorageHelper
{
    public static function makeRatesHash(CurrencyRateInterface $object): string
    {
        return md5(json_encode([
            'rat' => $object->getCurrencyCode(),
            'rel' => $object->getCurrencyRel()
        ]));
    }

    public static function makeBalanceHash(CurrencyBalanceInterface $object): string
    {
        return md5($object->getCurrencyCode());
    }
}