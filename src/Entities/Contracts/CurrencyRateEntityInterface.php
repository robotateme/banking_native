<?php

namespace Banking\Entities\Contracts;

interface CurrencyRateEntityInterface extends EntityInterface
{
    /**
     * @return float
     */
    public function getValue(): float;

    /**
     * @return string
     */
    public function getCurrencyCode(): string;

    /**
     * @return string
     */
    public function getCurrencyRel(): string;

    /**
     * @return string
     */
    public function getKey(): string;

    public function setValue(float $value): void;
}