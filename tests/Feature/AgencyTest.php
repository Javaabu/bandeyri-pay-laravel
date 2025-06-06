<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Tests\TestCase;

class AgencyTest extends TestCase
{

    public function test_outgoing_request_for_agency_information_is_correct()
    {
        Http::fake([
            $this->test_api_url . '/agency' => Http::response([], 200)
        ]);

        $bandeyriPay = app(BandeyriPay::class);
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());
        $bandeyriPay->getAgency();

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return $request->url() === $this->test_api_url . '/agency';
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

    public function test_it_can_retrieve_agency_information()
    {
        Http::fake([
            $this->test_api_url . '/agency' => Http::response([
                'status' => 'success',
                'data' => [
                    'name' => 'Ministry of Finance',
                    'business_area' => 'SZwDh',
                    'timezone' => 'Indian/Maldives',
                    'type' => 'Ministry',
                    'domain' => 'finance.gov.mv',
                    'additional_domains' => [],
                    'transaction_types' => [
                        [
                            'name' => 'Offline',
                            'settings' => [],
                        ],
                    ],
                    'contacts' => [
                        'agency' => [
                            'address' => 'Some address',
                            'email' => 'test@example.com',
                            'phone' => '1234567890'
                        ],
                        'focal_point' => [
                            'name' => 'John Doe',
                            'email' => 'john@example.com',
                            'phone' => '1234567890'
                        ],
                    ]
                ]
            ], 200),
        ]);

        // Create an instance of the BandeyriPay class
        $bandeyriPay = new BandeyriPay();
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());

        // Get the agency info
        $agencyInfo = $bandeyriPay->getAgency();
        $agencyInfoArray = $agencyInfo->toArray();
        $agencyDto = $agencyInfo->toDto();

        // Assert the response is as expected
        $this->assertEquals('success', $agencyInfoArray['status']);
        $this->assertEquals('Ministry of Finance', $agencyInfoArray['data']['name']);
        $this->assertEquals('SZwDh', $agencyInfoArray['data']['business_area']);
        $this->assertEquals('Indian/Maldives', $agencyInfoArray['data']['timezone']);

        // test it can convert response into a DTO of AgencyResponse
        $this->assertInstanceOf(AgencyResponse::class, $agencyDto->data);
    }
}
