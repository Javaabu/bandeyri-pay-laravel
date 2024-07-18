<?php

namespace Javaabu\BandeyriGateway\Responses\Transaction;

use Illuminate\Support\Carbon;
use Javaabu\BandeyriGateway\Contracts\ResponseContract;

class TransactionResponse implements ResponseContract
{
    public function __construct(
        public ?string $id,
        public ?string $type,
        public ?ProviderResponse $provider,
        public ?string $currency,
        public ?string $state,
        public ?array $history,
        public ?AgencyResponse $agency,
        public ?array $purposes,
        public ?CustomerResponse $customer,
        public ?PayerResponse $payer,
        public ?string $local_id,
        public ?string $customer_reference,
        public ?string $return_url,
        public ?Carbon $expires_at,
        public ?string $url,
        public ?int $amount,
        public ?string $amount_in_major_unit,
        public ?string $receipt_no,
    ) {
    }

    public static function from(array $data): TransactionResponse
    {
        $purposes = collect(data_get($data, 'purposes', []))
            ->map(fn (array $purpose) => PurposeResponse::from($purpose))
            ->toArray();

        $history = collect(data_get($data, 'history', []))
            ->map(fn (array $history) => TransactionHistoryResponse::from($history))
            ->toArray();

        return new self(
            id: data_get($data, 'id'),
            type: data_get($data, 'type'),
            provider: ProviderResponse::from(data_get($data, 'provider', [])),
            currency: data_get($data, 'currency'),
            state: data_get($data, 'state'),
            history: $history,
            agency: AgencyResponse::from(data_get($data, 'agency', [])),
            purposes: $purposes,
            customer: CustomerResponse::from(data_get($data, 'customer', [])),
            payer: PayerResponse::from(data_get($data, 'payer', [])),
            local_id: data_get($data, 'local_id'),
            customer_reference: data_get($data, 'customer_reference'),
            return_url: data_get($data, 'return_url'),
            expires_at: data_get($data, 'expires_at') ? Carbon::parse(data_get($data, 'expires_at')) : null,
            url: data_get($data, 'url'),
            amount: data_get($data, 'amount'),
            amount_in_major_unit: data_get($data, 'amount_in_major_unit'),
            receipt_no: data_get($data, 'receipt_no'),
        );
    }
}
