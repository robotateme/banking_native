<?php

namespace Banking\Factories;

use Banking\Entities\Account;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Factories\Contracts\FactoryInterface;

class AccountFactory implements FactoryInterface
{
    /**
     * @param  BankEntityInterface  $bank
     * @return Account
     */
    public static function create(BankEntityInterface $bank): Account {
        return new Account($bank);
    }
}