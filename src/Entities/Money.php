<?php
declare(strict_types=1);
namespace Banking\Entities;

use Banking\Entities\Contracts\BankEntityInterface;
use Banking\Entities\Contracts\MoneyInterface;
use Banking\Exceptions\Values\WrongCurrencyCodeException;
use Banking\ValueObjects\CurrencyCodeValue;

class Money implements MoneyInterface
{
    public function __construct(
        readonly private BankEntityInterface $bank,
        private float $amount,
        private string $currencyCode
    ) {

    }

    /**
     * @param  string  $currencyCodeTo
     * @return Money
     * @throws WrongCurrencyCodeException
     */
    public function exchangeTo(string $currencyCodeTo): static
    {
        $currencyCodeTo = (new CurrencyCodeValue($currencyCodeTo))->getValue();
        $amount = $this->bank->exchange(
            $this->currencyCode,
            $currencyCodeTo,
            $this->amount
        );

        $this->currencyCode = $currencyCodeTo;
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}