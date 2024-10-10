<?php

namespace Banking\Exceptions\Entities\Contracts;

use Banking\Exceptions\Contracts\BaseException;
use Throwable;

abstract class BaseEntityException extends BaseException
{
    protected $message = "";
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if (empty($message)) {
            $message = $this->message;
        }

        parent::__construct($message, $code, $previous);
    }
}