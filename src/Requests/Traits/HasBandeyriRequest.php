<?php

namespace Javaabu\BandeyriGateway\Requests\Traits;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriGateway\BandeyriGateway;
use Javaabu\BandeyriGateway\Responses\BandeyriGatewayResponse;

trait HasBandeyriRequest
{
    protected array $query_params = [];

    public function __construct(
        public BandeyriGateway $bandeyriGateway,
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

    public function send(): ?BandeyriGatewayResponse
    {
        $request_method = $this->getRequestMethod();
        $sanitized_request_method = str($request_method)->lower()->__toString();
        $request_url = $this->getUrl();

        $response = match ($sanitized_request_method) {
            'post' => Http::withHeaders($this->bandeyriGateway->getHeaders())->post($request_url, $this->defaultBody()),
            default => Http::withHeaders($this->bandeyriGateway->getHeaders())->get($request_url, $this->defaultBody()),
        };

        return new BandeyriGatewayResponse($response, $this);
    }

}
