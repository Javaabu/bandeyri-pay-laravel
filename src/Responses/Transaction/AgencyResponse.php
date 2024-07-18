<?php

namespace Javaabu\BandeyriPay\Responses\Transaction;

use Javaabu\BandeyriPay\Contracts\ResponseContract;

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
