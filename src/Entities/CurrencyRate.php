<?php
declare(strict_types=1);
namespace Banking\Entities;

use Banking\Entities\Contracts\CurrencyRateInterface;
use Banking\Exceptions\Values\WrongCurrencyRateValueException;
use Banking\ValueObjects\CurrencyRateValue;

class CurrencyRate implements CurrencyRateInterface
{
    private float $value;

    /**
     * @param  string  $currencyCode
     * @param  string  $currencyRel
     * @param  float  $value
     * @throws WrongCurrencyRateValueException
     */
    public function __construct(
        private readonly string $currencyCode,
        private readonly string $currencyRel,
        float $value,
    ) {
        $rateValue = new CurrencyRateValue($value);
        $this->value = $rateValue->getValue();
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

    /**
     * @throws WrongCurrencyRateValueException
     */
    public function makeConverse(): static
    {
        return new static($this->currencyRel, $this->currencyCode, 1/$this->value);
    }

    public function getKey(): string
    {
        return "$this->currencyCode - $this->currencyRel";
    }
}