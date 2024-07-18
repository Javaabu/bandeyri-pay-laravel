<?php

namespace Javaabu\BandeyriGateway\Responses;

use Javaabu\BandeyriGateway\Contracts\ResponseContract;
use Javaabu\BandeyriGateway\Responses\Common\LinkResponse;
use Javaabu\BandeyriGateway\Responses\Common\MetaResponse;

class CollectionResponse implements ResponseContract
{
    public function __construct(
        public ?string $status,
        public mixed $data,
        public ?LinkResponse $links,
        public ?MetaResponse $meta,
    ) {
    }

    public static function from(array $data): CollectionResponse
    {
        return new self(
            status: data_get($data, 'status'),
            data: data_get($data, 'data'),
            links: LinkResponse::from(data_get($data, 'links', [])),
            meta: MetaResponse::from(data_get($data, 'meta', [])),
        );
    }

    public function hasPagination(): bool
    {
        return $this->links->next || $this->links->previous;
    }

}
