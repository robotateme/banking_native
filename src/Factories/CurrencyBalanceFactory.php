<?php
declare(strict_types=1);
namespace Banking\Factories;

use Banking\Entities\Contracts\CurrencyBalanceEntityInterface;
use Banking\Entities\CurrencyBalance;
use Banking\Exceptions\Values\WrongBalanceAmountException;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\Factories\Contracts\FactoryInterface;
use Banking\ValueObjects\BalanceAmountValue;
use Banking\ValueObjects\CurrencyCodeValue;

class CurrencyBalanceFactory implements FactoryInterface
{
    /**
     * @param  float  $amount
     * @param  string  $currency
     * @return CurrencyBalance
     * @throws WrongCurrencyCodeException
     * @throws WrongBalanceAmountException
     */
    public static function create(float $amount, string $currency): CurrencyBalanceEntityInterface
    {
        return new CurrencyBalance(
            (new BalanceAmountValue($amount))->getValue(),
            (new CurrencyCodeValue($currency))->getValue()
        );
    }
}