<?php

namespace Javaabu\BandeyriGateway\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Javaabu\BandeyriGateway\BandeyriGateway
 */
class BandeyriGateway extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Javaabu\BandeyriGateway\BandeyriGateway::class;
    }
}
