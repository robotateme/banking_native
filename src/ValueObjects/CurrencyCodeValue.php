<?php
declare(strict_types=1);
namespace Banking\ValueObjects;


use Banking\Enums\CurrencyCodesEnum;
use Banking\Exceptions\Values\WrongCurrencyCodeException;

readonly class CurrencyCodeValue
{
    /**
     * @throws WrongCurrencyCodeException
     */
    public function __construct(private string $currencyCode)
    {
        if (!CurrencyCodesEnum::tryFrom($this->currencyCode)) {
            throw new WrongCurrencyCodeException($this->currencyCode);
        }
    }

    public function __toString(): string
    {
        return $this->currencyCode;
    }

    public function getValue(): string
    {
        return $this->currencyCode;
    }
}