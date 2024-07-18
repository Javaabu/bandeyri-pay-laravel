<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Tests\TestCase;

class AgencyTest extends TestCase
{

    public function test_it_can_retrieve_agency_information()
    {
        Http::fake([
            $this->test_api_url . '/token' => Http::response([
                'token_type' => 'Bearer',
                'expires_in' => 31536000,
                'access_token' => 'test_token'
            ], 200),
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
