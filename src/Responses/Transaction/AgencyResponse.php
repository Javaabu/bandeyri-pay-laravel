<?php

namespace Javaabu\BandeyriGateway\Responses\Transaction;

use Javaabu\BandeyriGateway\Contracts\ResponseContract;

class AgencyResponse implements ResponseContract
{
    public function __construct(
        public ?string $name,
        public ?string $business_area,
    ) {
    }

    public static function from(array $data): AgencyResponse
    {
        return new self(
            name: data_get($data, 'name'),
            business_area: data_get($data, 'business_area'),
        );
    }
}
