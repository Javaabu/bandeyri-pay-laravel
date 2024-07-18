<?php

namespace Javaabu\BandeyriGateway\Responses\Transaction;

use Javaabu\BandeyriGateway\Contracts\ResponseContract;

class CustomerResponse implements ResponseContract
{
    public function __construct(
        public ?string $type,
        public ?string $id,
        public ?string $name,
    ) {
    }

    public static function from(array $data): CustomerResponse
    {
        return new self(
            type: data_get($data, 'type'),
            id: data_get($data, 'id'),
            name: data_get($data, 'name'),
        );
    }
}
