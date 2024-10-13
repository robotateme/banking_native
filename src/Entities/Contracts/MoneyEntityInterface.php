<?php
declare(strict_types=1);
namespace Banking\Entities\Contracts;

use Banking\Entities\MoneyEntity;
use Banking\Exceptions\Values\WrongCurrencyCodeException;

interface MoneyEntityInterface
{
    /**
     * @param  string  $currencyCodeTo
     * @return MoneyEntity
     * @throws WrongCurrencyCodeException
     */
    public function exchangeTo(string $currencyCodeTo): static;

    public function getAmount(): float;

    public function getCurrencyCode(): string;
}