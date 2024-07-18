<?php

namespace Javaabu\BandeyriPay\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriPay\Contracts\ResponseContract;
use Javaabu\BandeyriPay\Responses\BandeyriPayResponse;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Requests\Traits\HasBandeyriRequest;

class GetAgencyInformationRequest implements BandeyriRequest
{
    use HasBandeyriRequest;

    public string $method = 'GET';

    public function resolveEndpoint(): string
    {
        return '/agency';
    }

    public function createDtoFromResponse(array $response_data): ResponseContract|Collection
    {
        return AgencyResponse::from($response_data);
    }

    public function get(): BandeyriPayResponse
    {
        return $this->send();
    }
}
