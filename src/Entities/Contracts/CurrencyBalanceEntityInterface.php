<?php
declare(strict_types=1);
namespace Banking\Entities\Contracts;

interface CurrencyBalanceEntityInterface extends EntityInterface
{
    /**
     * @return string
     */
    public function getCurrencyCode(): string;

    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @param  float  $value
     * @return float
     */
    public function deposit(float $value): float;

    /**
     * @param  float  $value
     * @return float
     */
    public function withdraw(float $value): float;
}