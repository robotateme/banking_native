<?php
declare(strict_types=1);
namespace Banking\Entities\Contracts;

interface CurrencyBalanceInterface
{
    public function getCurrencyCode(): string;

    public function getAmount(): float;
}