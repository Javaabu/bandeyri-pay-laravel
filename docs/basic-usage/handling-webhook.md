---
title: Handling Webhook
sidebar_position: 2.5
---

# Handling Webhook

The Bandeyri Pay API sends a webhook notification to your application when a transaction is completed. You can use this notification to update your database or send an email to the customer.

## Receiving the webhook

You can receive the webhook notification by creating a route in your application. The route should point to a controller method that will handle the webhook notification.

```php
use Illuminate\Http\Request;

Route::post('/webhook', function (Request $request) {
    // Handle the webhook notification
});
```

## Verifying the webhook

You should verify the webhook notification to ensure that it was sent by the Bandeyri Pay API. You can verify the webhook by using the helper methods provided by the Bandeyri Pay package.

```php
use Illuminate\Http\Request;

Route::post('/webhook', function (Request $request) {
    $webhook_response = \Javaabu\BandeyriPay\Responses\Webhook\WebhookResponse::fromRequest($request);
    
    $is_valid_signature = bandeyriPay()->isValidSignature($webhook_response);
});
```

## Creating Signature On You Own

You can also create the signature on your own using the `WebhookResponse` object.

```php
use Illuminate\Http\Request;

Route::post('/webhook', function (Request $request) {
    $webhook_response = \Javaabu\BandeyriPay\Responses\Webhook\WebhookResponse::fromRequest($request);
    $signature_string = $request->header('x-bpg-signature');
    $signature_array = explode(',', $signature_string);
    $timestamp = data_get($signature_array, 0);   
    
    $signature = bandeyriPay()->makeSignature(
        $request->input('id'),
        $request->input('state'),
        $request->input('customer_reference'),
        $request->input('local_id'),
        $request->input('created_at'),
        $timestamp,
    );
});
```
