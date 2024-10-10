<?php

namespace Banking\Entities;

class CurrencyRate
{
    public function __construct(
        public string $currencyCode,
        public string $currencyRel,
        public float $value,
    ) {}
}