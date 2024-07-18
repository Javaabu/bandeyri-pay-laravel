<?php

namespace Javaabu\BandeyriPay\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriPay\Contracts\ResponseContract;
use Javaabu\BandeyriPay\Responses\BandeyriPayResponse;
use Javaabu\BandeyriPay\Requests\Traits\HasBandeyriRequest;
use Javaabu\BandeyriPay\Responses\Purpose\AgencyPurposeResponse;

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

    public function get(): BandeyriPayResponse
    {
        return $this->send();
    }
}
