<?php

namespace Javaabu\BandeyriPay;

use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriGateway\Exceptions\InvalidData;
use Javaabu\BandeyriGateway\Exceptions\ActionFailed;
use Javaabu\BandeyriGateway\Exceptions\Unauthorized;
use Javaabu\BandeyriGateway\DataObjects\TransactionData;
use Javaabu\BandeyriGateway\Exceptions\ResourceNotFound;
use Javaabu\BandeyriGateway\Requests\GetTransactionsRequest;
use Javaabu\BandeyriGateway\Requests\CreateTransactionRequest;
use Javaabu\BandeyriGateway\Requests\GetAgencyPurposesRequest;
use Javaabu\BandeyriGateway\Responses\BandeyriGatewayResponse;
use Javaabu\BandeyriGateway\Requests\GetAgencyInformationRequest;
use Javaabu\BandeyriGateway\Responses\Transaction\TransactionResponse;

class BandeyriGateway
{
    private string $api_url;

    private string $client_id;
    private string $client_secret;
    private ?string $bearer_token;

    public function __construct()
    {
        $this->client_id = config('services.bandeyri_gateway.client_id');
        $this->client_secret = config('services.bandeyri_gateway.client_secret');
        $this->api_url = config('services.bandeyri_gateway.bandeyri_api_url');
        $this->bearer_token = $this->setBearerToken();
    }

    private function setBearerToken()
    {
        $token_url = $this->api_url . '/token';
        $response = Http::post($token_url, [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        return $response->json('access_token');
    }

    public function getApiUrl(): string
    {
        return $this->api_url;
    }

    public function getHeaders(): array
    {
        return [
            'authorization' => "Bearer $this->bearer_token",
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'x-bpg-api' => 'v1',
        ];
    }

    public function getAgency(): BandeyriGatewayResponse
    {
        $agency_request = new GetAgencyInformationRequest($this);
        return $agency_request->get();
    }

    public function getPurposes(): BandeyriGatewayResponse
    {
        $purpose_request = new GetAgencyPurposesRequest($this);
        return $purpose_request->get();
    }

    public function getTransactions(): BandeyriGatewayResponse
    {
        $transaction_request = new GetTransactionsRequest($this);
        return $transaction_request->get();
    }

    public function paginateTransactions(int $page = 1): BandeyriGatewayResponse
    {
        $transaction_request = new GetTransactionsRequest($this);
        return $transaction_request->paginate($page);
    }

    public function getTransactionById(string $transaction_id): BandeyriGatewayResponse
    {
        $transaction_request = new GetTransactionsRequest($this, $transaction_id);
        return $transaction_request->get();
    }

    /**
     * @throws ActionFailed
     * @throws InvalidData
     * @throws Unauthorized
     * @throws ResourceNotFound
     */
    public function getTransactionUrl(string $transaction_id): string
    {
        $transaction = $this->getTransactionById($transaction_id);
        $transaction_dto = $transaction->toDto();
        /* @var TransactionResponse $transaction_data */
        $transaction_data = $transaction_dto->data;
        return $transaction_data->url;
    }

    public function createTransaction(TransactionData $data): BandeyriGatewayResponse
    {
        $transaction_request = new CreateTransactionRequest($this, $data);
        return $transaction_request->create();
    }

}
