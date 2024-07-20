<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Tests\TestCase;

class WebhookTest extends TestCase
{

    // check if the `isValidSignature` can validate the signature returned by the webhook
    public function test_webhook_signature_is_valid()
    {
        $bandeyriPay = new BandeyriPay();
        $bandeyriPay->setBearerToken('test_token');
        $bandeyriPay->setExpiresAt(now()->addYear());

        $payload = [
            'type' => 'transaction.state',
            'created_at' => '2021-10-14T05:16:28.000000Z',
            'data' => [
                'id' => '5e22e1037ac57f000841efff',
                'local_id' => '123456789',
                'customer_reference' => 'invoice 1',
                'state' => 'PP_TRANSACTION_GENERATED',
            ]
        ];

        $this->assertTrue($bandeyriPay->isValidSignature($payload));
    }

}
