<?php
declare(strict_types=1);
namespace Banking\ValueObjects;


use Banking\Enums\CurrencyCodesEnum;
use Banking\Exceptions\Values\WrongCurrencyCodeException;

readonly class CurrencyCodeValue
{
    public function __construct(private string $currencyCode)
    {

    }

    /**
     * @throws WrongCurrencyCodeException
     */
    public function getValue(): string
    {
        if (!CurrencyCodesEnum::tryFrom($this->currencyCode)) {
            throw new WrongCurrencyCodeException($this->currencyCode);
        }

        return $this->currencyCode;
    }
}