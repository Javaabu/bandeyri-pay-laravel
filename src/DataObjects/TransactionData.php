<?php

namespace Javaabu\BandeyriPay\DataObjects;

class TransactionData implements DataObject
{
    public function __construct(
        public ?string $currency,
        public ?array $purposes,
        public ?CustomerData $customer,
        public ?string $return_url,
        public ?string $local_id,
        public ?string $customer_reference,
    ) {
    }

    public static function fromArray(array $data): TransactionData
    {
        $purposes = collect(data_get($data, 'purposes', []))
            ->map(fn (array $purpose) => PurposeData::fromArray($purpose))
            ->toArray();

        return new self(
            currency: data_get($data, 'currency'),
            purposes: $purposes,
            customer: CustomerData::fromArray(data_get($data, 'customer', [])),
            return_url: data_get($data, 'return_url'),
            local_id: data_get($data, 'local_id'),
            customer_reference: data_get($data, 'customer_reference'),
        );
    }
}
