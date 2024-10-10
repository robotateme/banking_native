<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Banking\Entities\Bank;
use Banking\Entities\CurrencyRate;
use Banking\ValueObjects\BalanceAmountValue;
use Banking\ValueObjects\CurrencyCodeValue;


$bank = new Bank([
    new CurrencyRate(new CurrencyCodeValue('USD'), new CurrencyCodeValue('RUB'), 70),
    new CurrencyRate(new CurrencyCodeValue('EUR'), new CurrencyCodeValue('RUB'), 80),
    new CurrencyRate(new CurrencyCodeValue('EUR'), new CurrencyCodeValue('USD'), 1),
]);

$account = $bank->newAccount();
$account->addCurrency(new CurrencyCodeValue('RUB'));
$account->addCurrency(new CurrencyCodeValue('EUR'));
$account->addCurrency(new CurrencyCodeValue('USD'));
$account->setDefaultCurrency(new CurrencyCodeValue('RUB'));
dump($account->getSupportedCurrencies());
$account->deposit(new CurrencyCodeValue('RUB'), new BalanceAmountValue(1000.0));
$account->deposit(new CurrencyCodeValue('EUR'), new BalanceAmountValue(50.0));
$account->deposit(new CurrencyCodeValue('USD'), new BalanceAmountValue(50.0));

dump($account->getSummaryBalance() . ' RUB');
dump($account->getSummaryBalance(new CurrencyCodeValue('USD')). ' USD');
dump($account->getSummaryBalance(new CurrencyCodeValue('EUR')). ' EUR');

$bank->setNewRateValue(new CurrencyRate(new CurrencyCodeValue('USD'), new CurrencyCodeValue('RUB'), 100));
$bank->setNewRateValue(new CurrencyRate(new CurrencyCodeValue('EUR'), new CurrencyCodeValue('RUB'), 150));

dump($account->getSummaryBalance() . ' RUB');

$account->setDefaultCurrency(new CurrencyCodeValue('EUR'));
dump($account->getSummaryBalance() . ' EUR');

$money = $account->withdraw(new CurrencyCodeValue('RUB'), new BalanceAmountValue(1000));
$moneyAmountEur = $money->exchangeTo(new CurrencyCodeValue('EUR'));

$account->deposit(new CurrencyCodeValue('EUR'), new BalanceAmountValue($moneyAmountEur));
dump($account->getSummaryBalance() . ' EUR');

$bank->setNewRateValue(new CurrencyRate(new CurrencyCodeValue('EUR'), new CurrencyCodeValue('RUB'), 120));
dump($account->getSummaryBalance() . ' EUR');

$account->setDefaultCurrency(new CurrencyCodeValue('RUB'));
$account->removeCurrency(new CurrencyCodeValue('EUR'));
$account->removeCurrency(new CurrencyCodeValue('USD'));
dump($account->getSupportedCurrencies());
dump($account->getSummaryBalance() . ' RUB');






