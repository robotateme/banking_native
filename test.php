<?php

use Banking\Entities\Bank;
use Banking\Enums\CurrenciesEnum;

require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';


$bank = new Bank();
$USD = $bank->setNewCurrencyRate(CurrenciesEnum::USD, CurrenciesEnum::RUB, 100);
$EUR = $bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 100);
$bank->setNewCurrencyRate(CurrenciesEnum::USD, CurrenciesEnum::EUR);

$account = $bank->newAccount();
$account->addCurrencyBalance(CurrenciesEnum::EUR);
$account->addCurrencyBalance(CurrenciesEnum::RUB);
$account->setDefaultCurrency(CurrenciesEnum::RUB);
dump($account->getSupportedCurrencies());

$account->deposit(CurrenciesEnum::EUR, 10);
$account->deposit(CurrenciesEnum::RUB, 100);

dump($account->getSummaryBalance() . ' RUB');
dump($account->getSummaryBalance(CurrenciesEnum::EUR) . ' EUR');
dump($account->getSummaryBalance(CurrenciesEnum::USD) . ' USD');

$money = $account->withdraw(CurrenciesEnum::RUB, 100);
dump($account->getSummaryBalance() . ' RUB');
//$account->deposit(CurrenciesEnum::EUR, $money->exchangeTo(CurrenciesEnum::EUR));
