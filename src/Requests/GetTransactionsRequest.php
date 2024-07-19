<?php

namespace Javaabu\BandeyriPay\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Contracts\ResponseContract;
use Javaabu\BandeyriPay\Responses\BandeyriPayResponse;
use Javaabu\BandeyriPay\Requests\Traits\HasBandeyriRequest;
use Javaabu\BandeyriPay\Responses\Transaction\TransactionResponse;

class GetTransactionsRequest implements BandeyriRequest
{
    use HasBandeyriRequest;

    public string $method = 'GET';

    public function __construct(
        public BandeyriPay $bandeyriGateway,
        public ?string $transaction_id = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        if ($this->transaction_id) {
            return "/transactions/$this->transaction_id";
        }

        return '/transactions';
    }

    public function createDtoFromResponse(array $response_data): ResponseContract|Collection
    {
        $data_array = collect();
        foreach ($response_data as $transaction) {
            $data_array->push(TransactionResponse::from($transaction));
        }

        return $data_array;
    }

    public function get(): BandeyriPayResponse
    {
        return $this->send();
    }

    public function paginate(int $page = 1): BandeyriPayResponse
    {
        $this->query_params = [
            'page' => $page,
        ];

        return $this->send();
    }
}
