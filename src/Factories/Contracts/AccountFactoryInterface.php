<?php

namespace Banking\Factories\Contracts;

use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\Contracts\BankEntityInterface;

interface AccountFactoryInterface extends FactoryInterface
{
    public static function create(BankEntityInterface $bank) : AccountEntityInterface;
}