<?php

namespace Javaabu\BandeyriPay\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Javaabu\BandeyriPay\BandeyriPayServiceProvider;
use Javaabu\BandeyriPay\Tests\TestSupport\Providers\TestServiceProvider;

abstract class TestCase extends BaseTestCase
{

    protected string $test_api_url = 'https://api.example.com';
    protected string $test_client_id = 'test-client-id';
    protected string $test_client_secret = 'test-client-secret';

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.key', 'base64:yWa/ByhLC/GUvfToOuaPD7zDwB64qkc/QkaQOrT5IpE=');

        $this->app['config']->set('session.serialization', 'php');

        config()->set('bandeyri-pay.bandeyri_api_url', $this->test_api_url);
        config()->set('bandeyri-pay.bandeyri_client_id', $this->test_client_id);
        config()->set('bandeyri-pay.bandeyri_client_secret', $this->test_client_secret);

    }

    protected function getPackageProviders($app)
    {
        return [
            BandeyriPayServiceProvider::class,
            TestServiceProvider::class
        ];
    }
}
