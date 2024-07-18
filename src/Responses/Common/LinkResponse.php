<?php

namespace Javaabu\BandeyriPay\Responses\Common;

use Javaabu\BandeyriPay\Contracts\ResponseContract;

class LinkResponse implements ResponseContract
{
    public function __construct(
        public ?string $previous,
        public ?string $next,
    ) {
    }

    public static function from(array $data): LinkResponse
    {
        return new self(
            previous: data_get($data, 'previous'),
            next: data_get($data, 'next'),
        );
    }
}
