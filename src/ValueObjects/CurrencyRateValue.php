<?php

namespace Banking\ValueObjects;

use Banking\ValueObjects\Contracts\ValueObjectInterface;

readonly class CurrencyRateValue implements ValueObjectInterface
{
    /**
     * @param  float  $value
     */
    public function __construct(private float $value)
    {}

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}