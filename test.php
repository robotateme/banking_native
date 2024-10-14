<?php

use Banking\Entities\Bank;
use Banking\Enums\CurrenciesEnum;

require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$bank = new Bank();
$usd = $bank->setNewCurrencyRate(CurrenciesEnum::USD, CurrenciesEnum::RUB, 100);
$eur = $bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 150);
$bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::USD);

$account = $bank->newAccount();
$account->addCurrencyBalance(CurrenciesEnum::RUB);
$account->addCurrencyBalance(CurrenciesEnum::EUR);
$account->addCurrencyBalance(CurrenciesEnum::USD);
$account->setDefaultCurrency(CurrenciesEnum::RUB);

$account->deposit(CurrenciesEnum::RUB, 1000);
$account->deposit(CurrenciesEnum::EUR, 50);
$account->deposit(CurrenciesEnum::USD, 50);

dd($account->getSummaryBalance());
