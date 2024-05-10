<?php

namespace App\Exception;

class RequestBodyConvertException extends \RuntimeException
{
    public function __construct(\Throwable $throwable)
    {
        parent::__construct('Error whiel unmarshalling request body', 0, $throwable);
    }
}
