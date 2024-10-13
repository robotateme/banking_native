<?php
declare(strict_types=1);
namespace Banking\Factories;

use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\Money;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Factories\Contracts\FactoryInterface;
use Banking\ValueObjects\BalanceAmountValue;
use Banking\ValueObjects\CurrencyCodeValue;

class MoneyFactory implements FactoryInterface
{
    /**
     * @param  BankEntityInterface  $bank
     * @param  float  $amount
     * @param  string  $currencyCode
     * @return Money
     * @throws WrongBalanceAmountException
     * @throws WrongCurrencyCodeException
     */
    public static function create(BankEntityInterface $bank, float $amount, string $currencyCode): Money
    {
        return new Money($bank,
            (new BalanceAmountValue($amount))->getValue(),
            (new CurrencyCodeValue($currencyCode))->getValue()
        );
    }
}