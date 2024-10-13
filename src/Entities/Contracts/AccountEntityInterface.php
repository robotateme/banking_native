<?php
declare(strict_types=1);
namespace Banking\Entities\Contracts;

use Banking\Entities\MoneyEntity;

interface AccountEntityInterface
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
     * @return void
     */
    public function deposit(string $currencyCode, float $amount): void;


    /**
     * @param  string  $currencyCode
     * @param  float  $amount
     * @return MoneyEntity
     */
    public function withdraw(string $currencyCode, float $amount): MoneyEntity;


    /**
     * @param  string|null  $currencyCode
     * @return float
     */
    public function getSummaryBalance(string $currencyCode = null): float;
}