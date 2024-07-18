<?php

namespace Javaabu\BandeyriPay\Requests\Traits;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Responses\BandeyriPayResponse;

trait HasBandeyriRequest
{
    protected array $query_params = [];

    public function __construct(
        public BandeyriPay $bandeyriGateway,
    ) {
    }

    public function getRequestMethod(): string
    {
        if (property_exists($this, 'method')) {
            return $this->method;
        }

        return 'GET';
    }

    public function defaultBody(): array
    {
        return [];
    }

    private function getUrl(): string
    {
        return $this->getEndPointUrl() . $this->getQueryParams();
    }

    public function getQueryParams(): string
    {
        if (empty($this->query_params)) {
            return '';
        }

        return '?' . http_build_query($this->query_params);
    }

    private function getEndPointUrl(): string
    {
        return $this->bandeyriGateway->getApiUrl() . $this->resolveEndpoint();
    }

    public function send(): ?BandeyriPayResponse
    {
        $request_method = $this->getRequestMethod();
        $sanitized_request_method = str($request_method)->lower()->__toString();
        $request_url = $this->getUrl();

        $response = match ($sanitized_request_method) {
            'post' => Http::withHeaders($this->bandeyriGateway->getHeaders())->post($request_url, $this->defaultBody()),
            default => Http::withHeaders($this->bandeyriGateway->getHeaders())->get($request_url, $this->defaultBody()),
        };

        return new BandeyriPayResponse($response, $this);
    }

}
