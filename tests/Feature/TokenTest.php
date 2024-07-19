<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Tests\TestCase;

class TokenTest extends TestCase
{

    public function test_outgoing_request_for_token_is_correct()
    {
        Http::fake([
            $this->test_api_url . '/token' => Http::response([
                'token_type' => 'Bearer',
                'expires_in' => 31536000,
                'access_token' => 'test_token',
            ], 200)
        ]);

        $bandeyri_gateway = app(BandeyriPay::class);
        $bandeyri_gateway->getBearerToken();

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return $request->url() === $this->test_api_url . '/token';
        });

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return $request->method() === 'POST';
        });

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return $request->data() === [
                    'client_id' => $this->test_client_id,
                    'client_secret' => $this->test_client_secret,
                ];
        });
    }

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
