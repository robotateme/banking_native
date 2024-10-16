<?php
declare(strict_types=1);
namespace Banking\Entities\Contracts;

use Banking\Entities\Money;

interface AccountEntityInterface extends EntityInterface
{
    /**
     * @param  string  $currencyCode
     * @return void
     */
    public function addCurrencyBalance(string $currencyCode): void;

    /**
     * @param  string  $currencyCode
     * @return void
     */
    public function removeCurrencyBalance(string $currencyCode): void;

    /**
     * @param  string  $currencyCode
     * @return void
     */
    public function setDefaultCurrency(string $currencyCode): void;

    /**
     * @return array
     */
    public function getSupportedCurrencies(): array;

    /**
     * @param  string  $currencyCode
     * @param  float  $amount
     * @return float
     */
    public function deposit(string $currencyCode, float $amount): float;


    /**
     * @param  string  $currencyCode
     * @param  float  $amount
     * @return Money
     */
    public function withdraw(string $currencyCode, float $amount): Money;


    /**
     * @param  string|null  $currencyCode
     * @return float
     */
    public function getSummaryBalance(string $currencyCode = null): float;

    /**
     * @return string|null
     */
    public function getDefaultCurrency(): ?string;
}