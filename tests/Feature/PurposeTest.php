<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Responses\Purpose\AgencyPurposeResponse;
use Javaabu\BandeyriPay\Tests\TestCase;

class PurposeTest extends TestCase
{

    public function test_it_can_retrieve_purposes()
    {
        Http::fake([
            $this->test_api_url . '/token' => Http::response([
                'token_type' => 'Bearer',
                'expires_in' => 31536000,
                'access_token' => 'test_token'
            ], 200),
            $this->test_api_url . '/purposes' => Http::response([
                'status' => 'success',
                'data' => [
                    [
                        "id" => "test-uuid",
                        "name" => "Test Fund",
                        "fund" => "T-FUND",
                        "cost_centre" => "COST-CENTRE",
                        "functional_area" => "00000",
                        "gl_code" => "000000",
                        "local_code" => "0000",
                    ]
                ],
                'meta' => [
                    'total' => 1
                ]
            ], 200),
        ]);

        // Create an instance of the BandeyriPay class
        $bandeyriPay = new BandeyriPay();

        // Get the purpose info
        $purposesInfo = $bandeyriPay->getPurposes();
        $purposesInfoArray = $purposesInfo->toArray();
        $purposesDto = $purposesInfo->toDto();

        // Assert the response is as expected
        $this->assertEquals('success', $purposesInfoArray['status']);
        $this->assertEquals('test-uuid', $purposesInfoArray['data'][0]['id']);
        $this->assertEquals('Test Fund', $purposesInfoArray['data'][0]['name']);
        $this->assertEquals('T-FUND', $purposesInfoArray['data'][0]['fund']);
        $this->assertEquals('COST-CENTRE', $purposesInfoArray['data'][0]['cost_centre']);
        $this->assertEquals('00000', $purposesInfoArray['data'][0]['functional_area']);
        $this->assertEquals('000000', $purposesInfoArray['data'][0]['gl_code']);
        $this->assertEquals('0000', $purposesInfoArray['data'][0]['local_code']);

        // test it can convert response into a DTO of AgencyPurposeResponse
        $this->assertInstanceOf(AgencyPurposeResponse::class, $purposesDto->data[0]);
    }
}
