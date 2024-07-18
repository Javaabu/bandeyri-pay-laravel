<?php

namespace Javaabu\BandeyriPay\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public function __construct(string $message = 'Invalid configuration')
    {
        parent::__construct($message);
    }
}
