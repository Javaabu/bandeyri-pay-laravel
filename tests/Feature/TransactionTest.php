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

    public function test_the_outgoing_request_for_transaction_pagination_is_correct()
    {

        Http::fake([
            $this->test_api_url . '/transactions' => Http::response([], 200),
        ]);

        // Create an instance of the BandeyriPay class
        $bandeyriPay = new BandeyriPay();
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());
        $bandeyriPay->getTransactions(1);

        // assert the url of the request
        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return $request->url() === $this->test_api_url . '/transactions'
                && $request->data() === ['page' => '1'];
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

    public function test_it_can_retrieve_transactions()
    {
        Http::fake([
            $this->test_api_url . '/token' => Http::response([
                'token_type' => 'Bearer',
                'expires_in' => 31536000,
                'access_token' => 'test_token'
            ], 200),
            $this->test_api_url . '/transactions' => Http::response([
                'status' => 'success',
                'data' => [
                    [
                        "id" => "3283a2c3-77d6-4039-bfbc-394eaa9b20f2",
                        "type" => "online",
                        "provider" => [
                            "id" => null,
                            "url" => null,
                            "meta" => null,
                            "name" => null,
                            "record_id" => null,
                        ],
                        "currency" => "MVR",
                        "state" => "CANCELLED",
                        "state_date" => "2024-07-18T06:31:12.000000Z",
                        "history" => [
                            [
                                "date" => "2024-07-18T06:25:19+00:00",
                                "state" => "NEW"
                            ],
                            [
                                "date" => "2024-07-18T06:30:19+00:00",
                                "state" => "CANCELLED"
                            ]
                        ],
                        "agency" => [
                            "name" => "Ministry of Islamic Affairs",
                            "business_area" => "0000",
                        ],
                        "purposes" => [
                            [
                                "id" => "purpose-uuid",
                                "fund" => "T-FUND",
                                "name" => "Test Fund",
                                "amount" => 5000,
                                "gl_code" => "000000",
                                "local_code" => null,
                                "cost_centre" => "C12779999",
                                "description" => "",
                                "functional_area" => "00000",
                                "amount_in_major_unit" => "50.00"
                            ]
                        ],
                        "customer" => [
                            "id" => "A000000",
                            "name" => "John Doe",
                            "type" => "Individual Local",
                        ],
                        "payer" => [
                            "name" => null,
                            "email" => null,
                            "phone" => null,
                        ],
                        "local_id" => "",
                        "customer_reference" => "",
                        "return_url" => "http://test.test/payment-gateways/bandeyri_pay",
                        "expires_at" => "2024-07-18T06:30:19+00:00",
                        "url" => "https://api.example.mv/transaction/3283a2c3-77d6-4039-bfbc-394eaa9b20f4?expires=1721284219&signature=d2bc9ea075847666e2905e5a6ddf77d7f482281d324762e5ecbb052",
                        "amount" => 5000,
                        "amount_in_major_unit" => "50.00",
                        "receipt_no" => null
                    ]
                ],
                'links' => [
                    "prev" => "",
                    "next" => "",
                ],
                'meta' => [
                    "total" => 3,
                    "current_page" => 1,
                    "per_page" => 15,
                ]
            ], 200),
        ]);

        // Create an instance of the BandeyriPay class
        $bandeyriPay = new BandeyriPay();

        // Get the purpose info
        $transactionsInfo = $bandeyriPay->getTransactions();
        $transactionsInfoArray = $transactionsInfo->toArray();
        $transactionsDto = $transactionsInfo->toDto();

        // Assert the response is as expected
        $this->assertEquals('success', $transactionsInfoArray['status']);
        $this->assertEquals('3283a2c3-77d6-4039-bfbc-394eaa9b20f2', $transactionsInfoArray['data'][0]['id']);
        $this->assertEquals('online', $transactionsInfoArray['data'][0]['type']);
        $this->assertEquals('MVR', $transactionsInfoArray['data'][0]['currency']);
        $this->assertEquals('CANCELLED', $transactionsInfoArray['data'][0]['state']);
        $this->assertEquals('2024-07-18T06:31:12.000000Z', $transactionsInfoArray['data'][0]['state_date']);
        $this->assertEquals('Ministry of Islamic Affairs', $transactionsInfoArray['data'][0]['agency']['name']);
        $this->assertEquals('0000', $transactionsInfoArray['data'][0]['agency']['business_area']);
        $this->assertEquals('purpose-uuid', $transactionsInfoArray['data'][0]['purposes'][0]['id']);
        $this->assertEquals('T-FUND', $transactionsInfoArray['data'][0]['purposes'][0]['fund']);
        $this->assertEquals('Test Fund', $transactionsInfoArray['data'][0]['purposes'][0]['name']);
        $this->assertEquals(5000, $transactionsInfoArray['data'][0]['purposes'][0]['amount']);
        $this->assertEquals('000000', $transactionsInfoArray['data'][0]['purposes'][0]['gl_code']);
        $this->assertEquals('C12779999', $transactionsInfoArray['data'][0]['purposes'][0]['cost_centre']);
        $this->assertEquals('00000', $transactionsInfoArray['data'][0]['purposes'][0]['functional_area']);
        $this->assertEquals('50.00', $transactionsInfoArray['data'][0]['purposes'][0]['amount_in_major_unit']);
        $this->assertEquals('John Doe', $transactionsInfoArray['data'][0]['customer']['name']);
        $this->assertEquals('Individual Local', $transactionsInfoArray['data'][0]['customer']['type']);
        $this->assertEquals('http://test.test/payment-gateways/bandeyri_pay', $transactionsInfoArray['data'][0]['return_url']);
        $this->assertEquals('2024-07-18T06:30:19+00:00', $transactionsInfoArray['data'][0]['expires_at']);
        $this->assertEquals('https://api.example.mv/transaction/3283a2c3-77d6-4039-bfbc-394eaa9b20f4?expires=1721284219&signature=d2bc9ea075847666e2905e5a6ddf77d7f482281d324762e5ecbb052', $transactionsInfoArray['data'][0]['url']);
        $this->assertEquals(5000, $transactionsInfoArray['data'][0]['amount']);
        $this->assertEquals('50.00', $transactionsInfoArray['data'][0]['amount_in_major_unit']);

        // assert links and meta
        $this->assertEquals('', $transactionsInfoArray['links']['prev']);
        $this->assertEquals('', $transactionsInfoArray['links']['next']);
        $this->assertEquals(3, $transactionsInfoArray['meta']['total']);
        $this->assertEquals(1, $transactionsInfoArray['meta']['current_page']);
        $this->assertEquals(15, $transactionsInfoArray['meta']['per_page']);


        // test it can convert response into a DTO TransactionResponse
        $this->assertInstanceOf(TransactionResponse::class, $transactionsDto->data[0]);
    }

    public function test_it_can_retrieve_paginated_transactions()
    {
        Http::fake([
            $this->test_api_url . '/token' => Http::response([
                'token_type' => 'Bearer',
                'expires_in' => 31536000,
                'access_token' => 'test_token'
            ], 200),
            $this->test_api_url . '/transactions' => Http::response([
                'status' => 'success',
                'data' => [],
                'links' => [
                    "prev" => "",
                    "next" => "https://api.example.com/transactions?page=2"
                ],
                'meta' => [
                    "total" => 50,
                    "current_page" => 1,
                    "per_page" => 15,
                ]
            ], 200),
        ]);


        // Create an instance of the BandeyriPay class
        $bandeyriPay = new BandeyriPay();

        // Get the purpose info
        $transactionsInfo = $bandeyriPay->getTransactions(1);
        $transactionsInfoArray = $transactionsInfo->toArray();

        // assert links and meta
        $this->assertEquals('', $transactionsInfoArray['links']['prev']);
        $this->assertEquals('https://api.example.com/transactions?page=2', $transactionsInfoArray['links']['next']);
        $this->assertEquals(50, $transactionsInfoArray['meta']['total']);
        $this->assertEquals(1, $transactionsInfoArray['meta']['current_page']);
        $this->assertEquals(15, $transactionsInfoArray['meta']['per_page']);
    }

}
