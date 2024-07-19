<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Responses\Purpose\AgencyPurposeResponse;
use Javaabu\BandeyriPay\Responses\Transaction\TransactionResponse;
use Javaabu\BandeyriPay\Tests\TestCase;

class TransactionTest extends TestCase
{

    public function test_the_outgoing_request_for_single_transaction_is_correct()
    {
        $transaction_id = 'test_transaction_uuid';
        Http::fake([
            $this->test_api_url . '/transactions/' . $transaction_id => Http::response([], 200),
        ]);

        // Create an instance of the BandeyriPay class
        $bandeyriPay = new BandeyriPay();
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());
        $bandeyriPay->getTransactionById($transaction_id);

        // assert the url of the request
        Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($transaction_id) {
            return $request->url() === $this->test_api_url . '/transactions/' . $transaction_id;
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

    public function test_it_can_retrieve_a_single_transaction()
    {
        $transaction_id = 'test_transaction_uuid';
        Http::fake([
            $this->test_api_url . '/transactions/' . $transaction_id => Http::response([
                'status' => 'success',
                'data' => [
                    "id" => "test_transaction_uuid",
                    "provider" => [
                        "id" => "",
                        "name" => "",
                        "url" => "",
                        "record_id" => ""
                    ],
                    "currency" => "MVR",
                    "state" => "NEW",
                    "history" => [
                        [
                            "state" => "NEW",
                            "date" => "2021-11-25T15:18:42+05:00"
                        ]
                    ],
                    "agency" => [
                        "name" => "Ministry of Islamic Affairs",
                        "business_area" => "uw2sH"
                    ],
                    "purposes" => [
                        [
                            "name" => "Purpose",
                            "fund" => "123",
                            "cost_centre" => "13",
                            "functional_area" => "123",
                            "gl_code" => "121073",
                            "amount" => "1020",
                            "amount_in_major_unit" => "10.20"
                        ]
                    ],
                    "local_id" => "",
                    "customer_reference" => "",
                    "return_url" => "",
                    "expires_at" => "2021-11-25T10:19:42+00:00",
                    "url" => $this->test_api_url . "/transaction/fc74ca1a67134986a3e7943372a4c2b3?expires=1637835582 signature=b8cc9318c0da329ecdc547ee45abc6ae5fffbc141cb40c8e980b77630e2f5890b"
                ],
            ], 200),
        ]);

        // Create an instance of the BandeyriPay class
        $bandeyriPay = new BandeyriPay();
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());

        $response = $bandeyriPay->getTransactionById($transaction_id);
        $transaction_dto = $response->toDto();
        $transaction_response = $transaction_dto->data;

        $this->assertInstanceOf(TransactionResponse::class, $transaction_response);
    }

}
