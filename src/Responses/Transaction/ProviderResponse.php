<?php

namespace Javaabu\BandeyriGateway\Responses\Transaction;

use Javaabu\BandeyriGateway\Contracts\ResponseContract;

class ProviderResponse implements ResponseContract
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $url,
        public ?string $record_id,
    ) {
    }

    public static function from(array $data): ProviderResponse
    {
        return new self(
            id: data_get($data, 'id'),
            name: data_get($data, 'name'),
            url: data_get($data, 'url'),
            record_id: data_get($data, 'record_id'),
        );
    }
}
