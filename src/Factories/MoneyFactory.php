<?php
declare(strict_types=1);
namespace Banking\Factories;

use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\Money;
use Banking\Factories\Contracts\FactoryInterface;

class MoneyFactory implements FactoryInterface
{
    /**
     * @param  BankEntityInterface  $bank
     * @param  float  $amount
     * @param  string  $currencyCode
     * @return Money
     */
    public static function create(BankEntityInterface $bank, float $amount, string $currencyCode): Money
    {
        return new Money($bank, $amount, $currencyCode);
    }
}