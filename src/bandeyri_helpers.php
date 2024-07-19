<?php

if (!function_exists('bandeyriPay')) {
    function bandeyriPay()
    {
        return app(\Javaabu\BandeyriPay\BandeyriPay::class);
    }
}
