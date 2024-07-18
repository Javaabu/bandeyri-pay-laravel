<?php

namespace Javaabu\BandeyriGateway\Responses\Purpose;

use Javaabu\BandeyriGateway\Contracts\ResponseContract;

class AgencyPurposeResponse implements ResponseContract
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $fund,
        public ?string $cost_centre,
        public ?string $functional_area,
        public ?string $gl_code,
        public ?string $local_code,
    ) {
    }

    public static function from(array $data): AgencyPurposeResponse
    {
        return new self(
            id: data_get($data, 'id'),
            name: data_get($data, 'name'),
            fund: data_get($data, 'fund'),
            cost_centre: data_get($data, 'cost_centre'),
            functional_area: data_get($data, 'functional_area'),
            gl_code: data_get($data, 'gl_code'),
            local_code: data_get($data, 'local_code'),
        );
    }

}
