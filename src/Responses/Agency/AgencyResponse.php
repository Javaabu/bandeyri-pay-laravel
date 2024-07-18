<?php

namespace Javaabu\BandeyriGateway\Responses\Agency;

use Javaabu\BandeyriGateway\Contracts\ResponseContract;

class AgencyResponse implements ResponseContract
{
    public function __construct(
        public string $name,
        public string $business_area,
        public string $timezone,
        public string $type,
        public string $domain,
        public array  $transaction_types,
        public array  $contacts,
    ) {
    }

    public static function from(array $data): ResponseContract
    {
        $transaction_types = collect(data_get($data, 'transaction_types', []))
            ->map(fn (array $transaction_type) => TransactionTypeResponse::from($transaction_type))
            ->toArray();

        $contacts = data_get($data, 'contacts', []);
        $contacts_dto = [];
        $contacts_dto[] = AgencyContactResponse::from(data_get($contacts, 'agency', []));
        $contacts_dto[] = AgencyFocalPointResponse::from(data_get($contacts, 'focal_point', []));

        return new self(
            name: data_get($data, 'name'),
            business_area: data_get($data, 'business_area'),
            timezone: data_get($data, 'timezone'),
            type: data_get($data, 'type'),
            domain: data_get($data, 'domain'),
            transaction_types: $transaction_types,
            contacts: $contacts_dto,
        );
    }
}
