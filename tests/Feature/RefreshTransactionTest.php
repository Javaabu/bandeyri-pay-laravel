<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Responses\Transaction\TransactionResponse;
use Javaabu\BandeyriPay\Tests\TestCase;

class RefreshTransactionTest extends TestCase
{

    public function test_outgoing_request_for_refreshing_transaction_is_correct()
    {
        $transaction_id = 'test_transaction_uuid';
        Http::fake([
            $this->test_api_url . '/refresh/' . $transaction_id => Http::response([], 200)
        ]);

        $bandeyriPay = app(BandeyriPay::class);
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());
        $bandeyriPay->refreshTransaction($transaction_id);

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($transaction_id) {
            return $request->url() === $this->test_api_url . '/refresh/' . $transaction_id;
        });

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return $request->method() === 'GET';
        });

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return data_get($request->headers(), 'authorization') === ["Bearer test_token"];
        });

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return data_get($request->headers(), 'accept') === ["application/json"];
        });

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return data_get($request->headers(), 'content-type') === ["application/json"];
        });

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return data_get($request->headers(), 'x-bpg-api') === ["v1"];
        });
    }


    public function test_refresh_transaction_provider_returns_response()
    {
        $transaction_id = 'test_transaction_uuid';
        Http::fake([
            $this->test_api_url . '/refresh/' . $transaction_id => Http::response([], 200)
        ]);

        $bandeyriPay = app(BandeyriPay::class);
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());
        $response = $bandeyriPay->refreshTransaction($transaction_id);

        $this->assertInstanceOf(TransactionResponse::class, $response->toDto()->data);
    }
}
