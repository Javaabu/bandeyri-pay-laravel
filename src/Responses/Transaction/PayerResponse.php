<?php

namespace Javaabu\BandeyriPay\Responses\Transaction;

use Javaabu\BandeyriPay\Contracts\ResponseContract;

class PayerResponse implements ResponseContract
{
    public function __construct(
        public ?string $name,
        public ?string $phone,
        public ?string $email,
    ) {
    }

    public static function from(array $data): PayerResponse
    {
        return new self(
            name: data_get($data, 'name'),
            phone: data_get($data, 'phone'),
            email: data_get($data, 'email'),
        );
    }
}
