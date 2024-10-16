<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Banking\Entities\Account;
use Banking\Entities\Bank;
use Banking\Enums\CurrenciesEnum;
use Banking\Exceptions\Entities\Contracts\BaseEntityException;
use Banking\Exceptions\Values\Contracts\BaseValueException;

try {
    $bank = new Bank();
    $usd = $bank->setNewCurrencyRate(CurrenciesEnum::USD, CurrenciesEnum::RUB, 70);
    $eur = $bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 80);
    $bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::USD);
    /** @var Account $account */
    $account = $bank->newAccount();
    $account->addCurrencyBalance(CurrenciesEnum::RUB);
    $account->addCurrencyBalance(CurrenciesEnum::EUR);
    $account->addCurrencyBalance(CurrenciesEnum::USD);

    $account->setDefaultCurrency(CurrenciesEnum::RUB);
    dump($account->getSupportedCurrencies());
    $account->deposit(CurrenciesEnum::RUB, 1000);
    $account->deposit(CurrenciesEnum::EUR, 50);
    $account->deposit(CurrenciesEnum::USD, 50);


    dump($account->getSummaryBalance().' RUB');
    dump($account->getSummaryBalance(CurrenciesEnum::USD).' USD');
    dump($account->getSummaryBalance(CurrenciesEnum::EUR).' EUR');
    $eur->setValue(150);
    $usd->setValue(100);

    dump($account->getSummaryBalance().' RUB');
    $account->setDefaultCurrency(CurrenciesEnum::EUR);
    dump($account->getSummaryBalance().' EUR');

    $money = $account->withdraw(CurrenciesEnum::RUB, 1000);

    $money->exchangeTo(CurrenciesEnum::EUR);
    $account->deposit(CurrenciesEnum::EUR, $money->getAmount());
    dump($account->getSummaryBalance().' EUR');


    $bank->setNewCurrencyRate(CurrenciesEnum::EUR, CurrenciesEnum::RUB, 120);
    dump($account->getSummaryBalance().' EUR');

    $account->setDefaultCurrency(CurrenciesEnum::RUB);
    $account->removeCurrencyBalance(CurrenciesEnum::EUR);
    $account->removeCurrencyBalance(CurrenciesEnum::USD);
    dump($account->getSupportedCurrencies());
    dump($account->getSummaryBalance().' RUB');


} catch (
BaseEntityException|BaseValueException $e) {
    dump($e->getMessage());
}








