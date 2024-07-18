<?php

namespace Javaabu\BandeyriGateway\Responses\Agency;

use Javaabu\BandeyriGateway\Contracts\ResponseContract;

class AgencyContactResponse implements ResponseContract
{
    public function __construct(
        public ?string $address,
        public ?string $email,
        public ?string $phone,
    ) {
    }

    public static function from(array $data): AgencyContactResponse
    {
        return new self(
            address: data_get($data, 'address'),
            email: data_get($data, 'email'),
            phone: data_get($data, 'phone'),
        );
    }
}
