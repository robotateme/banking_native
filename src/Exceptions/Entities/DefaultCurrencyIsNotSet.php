<?php
declare(strict_types=1);
namespace Banking\Exceptions\Entities;

use Banking\Exceptions\Entities\Contracts\BaseEntityException;

class DefaultCurrencyIsNotSet extends Contracts\BaseEntityException
{
    protected $message = "The default currency is not set";
}