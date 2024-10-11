<?php
declare(strict_types=1);
namespace Banking\Enums;

enum CurrencyCodesEnum : string
{
    case RUB = 'RUB';
    case EUR = 'EUR';
    case USD = 'USD';
}