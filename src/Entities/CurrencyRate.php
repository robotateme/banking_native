<?php

namespace Banking\Entities;

class CurrencyRate
{
    /**
     * @param  string  $currencyCode
     * @param  string  $currencyRel
     * @param  float  $value
     */
    public function __construct(
        private readonly string $currencyCode,
        private readonly string $currencyRel,
        private float $value,
    ) {}

    /**
     * @param  float  $value
     * @return void
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @return string
     */
    public function getCurrencyRel(): string
    {
        return $this->currencyRel;
    }
}