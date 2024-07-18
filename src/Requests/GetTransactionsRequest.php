<?php

namespace Javaabu\BandeyriGateway\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriGateway\BandeyriGateway;
use Javaabu\BandeyriGateway\Contracts\ResponseContract;
use Javaabu\BandeyriGateway\Responses\BandeyriGatewayResponse;
use Javaabu\BandeyriGateway\Requests\Traits\HasBandeyriRequest;
use Javaabu\BandeyriGateway\Responses\Transaction\TransactionResponse;

class GetTransactionsRequest implements BandeyriRequest
{
    use HasBandeyriRequest;

    public string $method = 'GET';

    public function __construct(
        public BandeyriGateway $bandeyriGateway,
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
        return TransactionResponse::from($response_data);
    }

    public function get(): BandeyriGatewayResponse
    {
        return $this->send();
    }

    public function paginate(int $page = 1): BandeyriGatewayResponse
    {
        $this->query_params = [
            'page' => $page,
        ];

        return $this->send();
    }
}
