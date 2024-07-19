<?php

namespace Javaabu\BandeyriPay\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Contracts\ResponseContract;
use Javaabu\BandeyriPay\Responses\BandeyriPayResponse;
use Javaabu\BandeyriPay\Responses\Agency\AgencyResponse;
use Javaabu\BandeyriPay\Requests\Traits\HasBandeyriRequest;
use Javaabu\BandeyriPay\Responses\Transaction\ProviderResponse;
use Javaabu\BandeyriPay\Responses\Transaction\TransactionResponse;

class RefreshTransactionRequest implements BandeyriRequest
{
    use HasBandeyriRequest;

    public string $method = 'GET';

    public function __construct(
        public BandeyriPay $bandeyriGateway,
        public string $transaction_id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/refresh/' . $this->transaction_id;
    }

    public function createDtoFromResponse(array $response_data): ResponseContract|Collection
    {
        return TransactionResponse::from($response_data);
    }

    public function get(): BandeyriPayResponse
    {
        return $this->send();
    }
}
