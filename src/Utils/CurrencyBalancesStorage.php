<?php
declare(strict_types=1);
namespace Banking\Utils;

use Banking\Entities\Contracts\CurrencyBalanceInterface;
use Banking\Entities\CurrencyBalance;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Utils\Helpers\StorageHelper;
use ReturnTypeWillChange;
use SplObjectStorage;
class CurrencyBalancesStorage extends SplObjectStorage
{
    /**
     * @throws UnsupportedCurrencyCode
     */
    public function find(string $currencyCode): ?CurrencyBalance
    {
        /** @var CurrencyBalance $balance */
        foreach ($this as $balance) {
            if ($balance->getCurrencyCode() === $currencyCode) {
                return $balance;
            }
        }

        throw new UnsupportedCurrencyCode();
    }

    public function attach(object $object, mixed $info = null): void
    {
        if ($object instanceof CurrencyBalanceInterface) {
            parent::attach($object, $info);
        }
    }

    #[ReturnTypeWillChange] public function getHash(object $object): ?string
    {
        /** @var CurrencyBalanceInterface $object */
        if ($object instanceof CurrencyBalanceInterface) {
            return StorageHelper::makeBalanceHash($object);
        }

        return null;
    }
}