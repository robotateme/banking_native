<?php
declare(strict_types=1);
namespace Banking\ValueObjects;

use Banking\Exceptions\Values\WrongCurrencyRateValueException;

readonly class CurrencyRateValue
{
    /**
     * @param  float  $value
     * @throws WrongCurrencyRateValueException
     */
    public function __construct(private float $value)
    {
        if ($this->value <= 0) {
            throw new WrongCurrencyRateValueException();
        }
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}