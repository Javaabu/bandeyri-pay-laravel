<?php

namespace Javaabu\BandeyriGateway\Exceptions;

use Exception;

class ActionFailed extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
