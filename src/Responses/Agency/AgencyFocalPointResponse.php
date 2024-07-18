<?php

namespace Javaabu\BandeyriGateway\Responses\Agency;

use Javaabu\BandeyriGateway\Contracts\ResponseContract;

class AgencyFocalPointResponse implements ResponseContract
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $phone,
    ) {
    }

    public static function from(array $data): AgencyFocalPointResponse
    {
        return new self(
            name: data_get($data, 'name'),
            email: data_get($data, 'email'),
            phone: data_get($data, 'phone'),
        );
    }
}
