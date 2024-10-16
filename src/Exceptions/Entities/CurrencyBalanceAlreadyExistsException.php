<?php
declare(strict_types=1);
namespace Banking\Exceptions\Entities;

use Banking\Exceptions\Entities\Contracts\BaseEntityException;

class CurrencyBalanceAlreadyExistsException extends BaseEntityException
{
    protected $message = "Balance with this currency already exists";
}