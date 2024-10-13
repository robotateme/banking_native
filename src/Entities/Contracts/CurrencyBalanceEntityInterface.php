<?php
declare(strict_types=1);
namespace Banking\Entities\Contracts;

interface CurrencyBalanceEntityInterface
{
    public function getCurrencyCode(): string;

    public function getAmount(): float;
}