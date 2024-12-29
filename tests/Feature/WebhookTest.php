<?php

namespace Javaabu\BandeyriPay\Tests\Feature;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Enums\TransactionStates;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Responses\Webhook\WebhookResponse;
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

        $created_at = Carbon::now()->subSeconds(10)->format('Y-m-d\TH:i:s.u\Z');
        $state = 'PP_TRANSACTION_GENERATED';
        $timestamp = '1634191588';
        $id = '5e22e1037ac57f000841efff';
        $local_id = '123456789';
        $customer_reference = 'invoice 1';

        $hash = $bandeyriPay->makeSignature(
            $id,
            $state,
            $customer_reference,
            $local_id,
            $created_at,
            $timestamp
        );

        $signature = $timestamp . '.v1=' . $hash;

        $webhookResponse = new WebhookResponse(
            type: 'transaction.state',
            created_at: $created_at,
            formatted_created_at: Carbon::parse($created_at),
            id: $id,
            local_id: $local_id,
            customer_reference: $customer_reference,
            state: TransactionStates::tryFrom($state),
            signature: $signature
        );


        $this->assertTrue($bandeyriPay->isValidSignature($webhookResponse));
    }

}
