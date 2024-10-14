<?php
declare(strict_types=1);
namespace Banking\Entities\Contracts;

use Banking\Entities\Money;
use Banking\Exceptions\Values\WrongCurrencyCodeException;

interface MoneyEntityInterface extends EntityInterface
{
    /**
     * @param  string  $currencyCodeTo
     * @return Money
     * @throws WrongCurrencyCodeException
     */
    public function exchangeTo(string $currencyCodeTo): static;

    public function getAmount(): float;

    public function getCurrencyCode(): string;
}