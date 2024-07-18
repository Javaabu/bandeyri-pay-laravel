<?php

namespace Javaabu\BandeyriGateway\DataObjects;

class PurposeData implements DataObject
{
    public function __construct(
        public ?string $id,
        public ?string $local_code,
        public ?int $amount,
    ) {
    }

    public static function fromArray(array $data): PurposeData
    {
        return new self(
            id: data_get($data, 'id'),
            local_code: data_get($data, 'local_code'),
            amount: data_get($data, 'amount'),
        );
    }
}
