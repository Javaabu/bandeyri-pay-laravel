<?php

namespace Javaabu\BandeyriPay\Responses\Transaction;

use Javaabu\BandeyriPay\Contracts\ResponseContract;

class PurposeResponse implements ResponseContract
{
    public function __construct(
        public ?string $name,
        public ?string $fund,
        public ?string $cost_centre,
        public ?string $functional_area,
        public ?string $gl_code,
        public ?int $amount,
        public ?string $amount_in_major_unit,
    ) {
    }

    public static function from(array $data): PurposeResponse
    {
        return new self(
            name: data_get($data, 'name'),
            fund: data_get($data, 'fund'),
            cost_centre: data_get($data, 'cost_centre'),
            functional_area: data_get($data, 'functional_area'),
            gl_code: data_get($data, 'gl_code'),
            amount: data_get($data, 'amount'),
            amount_in_major_unit: data_get($data, 'amount_in_major_unit'),
        );
    }

}
