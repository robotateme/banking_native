<?php

namespace Banking\Factories\Contracts;

use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\Contracts\MoneyEntityInterface;

interface MoneyFactoryInterface
{
    public static function create(BankEntityInterface $bank, float $amount, string $currencyCode): MoneyEntityInterface;
}