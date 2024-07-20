<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\DataObjects\TransactionData;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Responses\Purpose\AgencyPurposeResponse;
use Javaabu\BandeyriPay\Responses\Transaction\TransactionResponse;
use Javaabu\BandeyriPay\Tests\TestCase;

class CreateTransactionsTest extends TestCase
{

    public function test_the_outgoing_request_for_transaction_creation_is_correct()
    {
        Http::fake([
            $this->test_api_url . '/transactions' => Http::response([], 200),
        ]);

        // Create an instance of the BandeyriPay class
        $bandeyriPay = new BandeyriPay();
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());

        $transaction_data = TransactionData::fromArray([
            'currency' => 'MVR',
            'purposes' => [
                [
                    'id' => 'purpose_id',
                    'amount' => 1000,
                ],
                [
                    'local_code' => 'purpose_local_code',
                    'amount' => 2000,
                ],
                [
                    'id' => 'purpose_id_two',
                    'local_code' => 'purpose_local_code_two',
                    'amount' => 4000,
                ],
            ],
            'customer' => [
                'type' => 'customer_type',
                'id' => 'customer_id',
                'name' => 'customer_name',
            ],
            'return_url' => 'return_url',
        ]);

        $bandeyriPay->createTransaction($transaction_data);

        // assert the url of the request
        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return $request->url() === $this->test_api_url . '/transactions';
        });

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return $request->method() === 'POST';
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

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return data_get($request->data(), 'currency') === 'MVR'
                && data_get($request->data(), 'purposes') === [
                    [
                        'id' => 'purpose_id',
                        'amount' => 1000,
                    ],
                    [
                        'local_code' => 'purpose_local_code',
                        'amount' => 2000,
                    ],
                    [
                        'id' => 'purpose_id_two',
                        'amount' => 4000,
                    ],
                ]
                && data_get($request->data(), 'customer') === [
                    'type' => 'customer_type',
                    'id' => 'customer_id',
                    'name' => 'customer_name',
                ]
                && data_get($request->data(), 'return_url') === 'return_url';
        });
    }

    public function test_it_can_create_transaction()
    {
        Http::fake([
            $this->test_api_url . '/transactions' => Http::response([], 200),
        ]);

        $bandeyriPay = new BandeyriPay();
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());

        $transaction_data = TransactionData::fromArray([]);
        $response = $bandeyriPay->createTransaction($transaction_data);

        $this->assertInstanceOf(TransactionResponse::class, $response->toDto()->data);
    }

}
