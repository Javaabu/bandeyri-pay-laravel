<?php

namespace Javaabu\BandeyriGateway\Responses;

use Exception;
use Illuminate\Http\Client\Response;
use Javaabu\BandeyriGateway\Exceptions\InvalidData;
use Javaabu\BandeyriGateway\Exceptions\ActionFailed;
use Javaabu\BandeyriGateway\Exceptions\Unauthorized;
use Javaabu\BandeyriGateway\Requests\BandeyriRequest;
use Javaabu\BandeyriGateway\Exceptions\ResourceNotFound;

class BandeyriGatewayResponse extends Response
{
    public function __construct(
        $response,
        public BandeyriRequest $request,
    ) {
        parent::__construct($response);
    }

    public function isSuccessful(): bool
    {
        return (int) substr($this?->status(), 0, 1) === 2;
    }

    public function toArray(): ?array
    {
        return json_decode((string) $this->body(), true) ?? [];
    }

    public function toJson(): mixed
    {
        return $this->json();
    }

    /**
     * @throws ActionFailed
     * @throws InvalidData
     * @throws Unauthorized
     * @throws ResourceNotFound
     */
    public function toDto(): CollectionResponse
    {
        if (! $this->isSuccessful()) {
            $this->handleResponseError();
        }

        $data = $this->toArray();
        $data_objects = $this->request->createDtoFromResponse(data_get($data, 'data', []));
        return CollectionResponse::from([
            'status' => data_get($data, 'status'),
            'data' => $data_objects,
            'links' => data_get($data, 'links', []),
            'meta' => data_get($data, 'meta', []),
        ]);
    }

    /**
     * @throws ActionFailed
     * @throws InvalidData
     * @throws Unauthorized
     * @throws ResourceNotFound
     * @throws Exception
     */
    public function handleResponseError()
    {
        if ($this->status() === 422) {
            throw new InvalidData(json_decode((string) $this->body(), true));
        }

        if ($this->status() === 404) {
            throw new ResourceNotFound();
        }

        if ($this->status() === 400) {
            throw new ActionFailed((string) $this->body());
        }

        if ($this->status() === 401) {
            throw new Unauthorized((string) $this->body());
        }

        throw new Exception((string) $this->body());
    }
}
