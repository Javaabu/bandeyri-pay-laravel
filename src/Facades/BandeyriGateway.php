<?php

namespace Javaabu\BandeyriPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Javaabu\BandeyriPay\BandeyriPay
 */
class BandeyriPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Javaabu\BandeyriPay\BandeyriPay::class;
    }
}
