<?php

namespace Javaabu\BandeyriGateway\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriGateway\Contracts\ResponseContract;
use Javaabu\BandeyriGateway\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriGateway\Responses\BandeyriGatewayResponse;
use Javaabu\BandeyriGateway\Requests\Traits\HasBandeyriRequest;

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

    public function get(): BandeyriGatewayResponse
    {
        return $this->send();
    }
}
