<?php

namespace Javaabu\BandeyriPay\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Contracts\ResponseContract;
use Javaabu\BandeyriPay\Responses\BandeyriPayResponse;
use Javaabu\BandeyriPay\Requests\Traits\HasBandeyriRequest;
use Javaabu\BandeyriPay\Responses\Transaction\TransactionResponse;

class GetSingleTransactionsRequest extends GetTransactionsRequest
{
    public function createDtoFromResponse(array $response_data): ResponseContract|Collection
    {
        return TransactionResponse::from($response_data);
    }
}
