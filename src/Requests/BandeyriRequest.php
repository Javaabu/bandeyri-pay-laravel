<?php

namespace Javaabu\BandeyriGateway\Requests;

use Illuminate\Support\Collection;
use Javaabu\BandeyriGateway\Contracts\ResponseContract;

interface BandeyriRequest
{
    public function getRequestMethod(): string;

    public function defaultBody(): array;

    public function createDtoFromResponse(array $response_data): ResponseContract|Collection;
}
