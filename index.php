<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Banking\Entities\Bank;
use Banking\Exceptions\Entities\DefaultCurrencyIsNotSet;
use Banking\Exceptions\Entities\UnsupportedCurrencyCode;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;


try {
    $bank = new Bank();
    $usd = $bank->setNewCurrencyRate('USD', 'RUB', 70);
    $eur = $bank->setNewCurrencyRate('EUR', 'RUB', 80);
    $bank->setNewCurrencyRate('EUR', 'USD');

    $account = $bank->newAccount();
    $account->addCurrencyBalance('RUB');
    $account->addCurrencyBalance('EUR');
    $account->addCurrencyBalance('USD');

    $account->setDefaultCurrency('RUB');
    dump($account->getSupportedCurrencies());

    $account->deposit('RUB', 1000);
    $account->deposit('EUR', 50);
    $account->deposit('USD', 50);

    dump($account->getSummaryBalance() . ' RUB');
    dump($account->getSummaryBalance('USD'). ' USD');
    dump($account->getSummaryBalance('EUR'). ' EUR');

    $usd->setValue(100);
    $eur->setValue(150);

    dump($account->getSummaryBalance() . ' RUB');
    $account->setDefaultCurrency('EUR');
    dump($account->getSummaryBalance() . ' EUR');

    $money = $account->withdraw('RUB', 1000);
    $account->deposit('EUR', $money->exchangeTo('EUR'));

    dump($account->getSummaryBalance() . ' EUR');
    $eur->setValue(120);
    dump($account->getSummaryBalance() . ' EUR');


    $account->setDefaultCurrency('RUB');
    $account->removeCurrencyBalance('EUR');
    $account->removeCurrencyBalance('USD');

    dump($account->getSupportedCurrencies());
    dump($account->getSummaryBalance() . ' RUB');

} catch (WrongCurrencyCodeException|UnsupportedCurrencyCode|WrongBalanceAmountException|DefaultCurrencyIsNotSet $e) {
    dump($e->getMessage());
}



/*




















*/






