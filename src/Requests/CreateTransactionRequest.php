<?php

namespace Javaabu\BandeyriPay\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriPay\BandeyriPay;
use Javaabu\BandeyriPay\Contracts\ResponseContract;
use Javaabu\BandeyriPay\DataObjects\TransactionData;
use Javaabu\BandeyriPay\Responses\BandeyriPayResponse;
use Javaabu\BandeyriPay\Requests\Traits\HasBandeyriRequest;
use Javaabu\BandeyriPay\Responses\Transaction\TransactionResponse;

class CreateTransactionRequest implements BandeyriRequest
{
    use HasBandeyriRequest;

    public string $method = 'POST';

    public function __construct(
        public BandeyriPay $bandeyriGateway,
        public TransactionData $transactionCreateData,
    ) {

    }

    public function resolveEndpoint(): string
    {
        return '/transactions';
    }

    public function defaultBody(): array
    {
        $purposes = $this->transactionCreateData->purposes;
        $purposes_array = [];
        if ($purposes) {
            foreach ($purposes as $purpose) {
                $purpose_array = [];

                if ($purpose->id) {
                    $purpose_array['id'] = $purpose->id;
                }

                if ($purpose->local_code) {
                    $purpose_array['local_code'] = $purpose->local_code;
                }

                $purpose_array['amount'] = $purpose->amount;

                $purposes_array[] = $purpose_array;
            }
        }

        $body_data = [
            'currency' => $this->transactionCreateData->currency,
            'purposes' => $purposes_array,
            'customer' => $this->transactionCreateData->customer,
            'return_url' => $this->transactionCreateData->redirectUrl,
        ];

        return $body_data;
    }

    public function createDtoFromResponse(array $response_data): ResponseContract|Collection
    {
        return TransactionResponse::from($response_data);
    }

    public function create(): BandeyriPayResponse
    {
        return $this->send();
    }
}
