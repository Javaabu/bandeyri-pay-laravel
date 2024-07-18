<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Tests\TestCase;

class TokenTest extends TestCase
{


    public function test_it_can_get_token()
    {
        Http::fake([
            'https://api.example.com/token' => Http::response([
                'token_type' => 'Bearer',
                'expires_in' => 31536000,
                'access_token' => 'test_token',
            ], 200)
        ]);

        $bandeyri_gateway = new BandeyriPay();

        $this->assertEquals('test_token', $bandeyri_gateway->getBearerToken());
    }

}
