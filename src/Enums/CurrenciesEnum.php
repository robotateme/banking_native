<?php
declare(strict_types=1);
namespace Banking\Enums;

enum CurrenciesEnum
{
    const string RUB = CurrencyCodesEnum::RUB->value;
    const string EUR = CurrencyCodesEnum::EUR->value;
    const string USD = CurrencyCodesEnum::USD->value;
}

