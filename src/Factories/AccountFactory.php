<?php
declare(strict_types=1);
namespace Banking\Factories;

use Banking\Entities\Account;
use Banking\Entities\Contracts\AccountEntityInterface;
use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Factories\Contracts\AccountFactoryInterface;

class AccountFactory implements AccountFactoryInterface
{
    /**
     * @param  BankEntityInterface  $bank
     * @return Account
     */
    public static function create(BankEntityInterface $bank): AccountEntityInterface {
        return new Account($bank);
    }
}