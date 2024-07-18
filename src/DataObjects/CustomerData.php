<?php

namespace Javaabu\BandeyriPay\DataObjects;

class CustomerData implements DataObject
{
    public function __construct(
        public ?string $type,
        public ?string $id,
        public ?string $name,
    ) {
    }

    public static function fromArray(array $data): CustomerData
    {
        return new self(
            type: data_get($data, 'type'),
            id: data_get($data, 'id'),
            name: data_get($data, 'name'),
        );
    }
}
