<?php

namespace Banking\Entities\Contracts;

interface CurrencyRateInterface
{
    public function setValue(float $value): void;

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
}