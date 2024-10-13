<?php

namespace Banking\Entities\Contracts;

interface CurrencyRateInterface
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
}