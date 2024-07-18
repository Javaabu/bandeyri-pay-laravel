<?php

namespace Javaabu\BandeyriGateway\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriGateway\Contracts\ResponseContract;
use Javaabu\BandeyriGateway\Responses\BandeyriGatewayResponse;
use Javaabu\BandeyriGateway\Requests\Traits\HasBandeyriRequest;
use Javaabu\BandeyriGateway\Responses\Purpose\AgencyPurposeResponse;

class GetAgencyPurposesRequest implements BandeyriRequest
{
    use HasBandeyriRequest;

    public string $method = 'GET';

    public function resolveEndpoint(): string
    {
        return '/purposes';
    }

    public function createDtoFromResponse(array $response_data): ResponseContract|Collection
    {
        $data_array = collect();
        foreach ($response_data as $purpose) {
            $data_array->push(AgencyPurposeResponse::from($purpose));
        }

        return $data_array;
    }

    public function get(): BandeyriGatewayResponse
    {
        return $this->send();
    }
}
