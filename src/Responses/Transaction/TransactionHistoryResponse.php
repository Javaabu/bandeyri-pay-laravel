<?php

namespace Javaabu\BandeyriPay\Responses\Transaction;

use Illuminate\Support\Carbon;
use Javaabu\BandeyriPay\Contracts\ResponseContract;

class TransactionHistoryResponse implements ResponseContract
{
    public function __construct(
        public ?string $state,
        public ?Carbon $date,
    ) {
    }

    public static function from(array $data): TransactionHistoryResponse
    {
        return new self(
            state: data_get($data, 'state'),
            date: data_get($data, 'date') ? Carbon::parse(data_get($data, 'date')) : null,
        );
    }

}
