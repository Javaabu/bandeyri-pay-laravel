<?php

namespace Javaabu\BandeyriPay\Responses\Common;

use Javaabu\BandeyriPay\Contracts\ResponseContract;

class MetaResponse implements ResponseContract
{
    public function __construct(
        public ?string $total,
        public ?string $per_page,
        public ?string $current_page,
    ) {
    }

    public static function from(array $data): MetaResponse
    {
        return new self(
            total: data_get($data, 'total'),
            per_page: data_get($data, 'per_page'),
            current_page: data_get($data, 'current_page'),
        );
    }
}
