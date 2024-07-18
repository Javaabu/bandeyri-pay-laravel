<?php

namespace Javaabu\BandeyriGateway\Responses\Agency;

use Javaabu\BandeyriGateway\Contracts\ResponseContract;

class TransactionTypeResponse implements ResponseContract
{
    public function __construct(
        public ?string $name,
        public ?array  $settings,
    ) {

    }

    public static function from(array $data): TransactionTypeResponse
    {
        return new self(
            name: data_get($data, 'name'),
            settings: data_get($data, 'settings'),
        );
    }
}
