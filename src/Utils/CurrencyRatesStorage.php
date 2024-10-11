<?php

namespace Banking\Utils;

use Banking\Entities\Contracts\CurrencyRateInterface;
use Banking\Utils\Helpers\StorageHelper;
use ReturnTypeWillChange;
use SplObjectStorage;

class CurrencyRatesStorage extends SplObjectStorage
{
    public function attach(object $object, mixed $info = null): void
    {
        if ($object instanceof CurrencyRateInterface) {
            parent::attach($object, $info);
        }
    }

    #[ReturnTypeWillChange] public function getHash(object $object): ?string
    {
        if ($object instanceof CurrencyRateInterface) {
            return StorageHelper::makeRatesHash($object);
        }

        return null;
    }
}