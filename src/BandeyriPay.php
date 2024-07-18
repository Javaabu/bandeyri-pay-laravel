<?php

namespace Javaabu\BandeyriPay;

use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\Exceptions\InvalidData;
use Javaabu\BandeyriPay\Exceptions\ActionFailed;
use Javaabu\BandeyriPay\Exceptions\Unauthorized;
use Javaabu\BandeyriPay\DataObjects\TransactionData;
use Javaabu\BandeyriPay\Exceptions\ResourceNotFound;
use Javaabu\BandeyriPay\Responses\BandeyriPayResponse;
use Javaabu\BandeyriPay\Requests\GetTransactionsRequest;
use Javaabu\BandeyriPay\Requests\CreateTransactionRequest;
use Javaabu\BandeyriPay\Requests\GetAgencyPurposesRequest;
use Javaabu\BandeyriPay\Requests\GetAgencyInformationRequest;
use Javaabu\BandeyriPay\Responses\Transaction\TransactionResponse;

class BandeyriPay
{
    private string $api_url;

    private string $client_id;
    private string $client_secret;
    private ?string $bearer_token;

    public function __construct()
    {
        $this->client_id = config('bandeyri-pay.bandeyri_client_id') ?? throw new InvalidArgumentException("Bandeyri API URL is not configured");
        $this->client_secret = config('bandeyri-pay.bandeyri_client_secret') ?? throw new InvalidArgumentException("Bandeyri Client ID is not configured");
        $this->api_url = config('bandeyri-pay.bandeyri_api_url') ?? throw new InvalidArgumentException("Bandeyri Client Secret is not configured");
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

    public function getBearerToken(): ?string
    {
        return $this->bearer_token;
    }

    public function getAgency(): BandeyriPayResponse
    {
        $agency_request = new GetAgencyInformationRequest($this);
        return $agency_request->get();
    }

    public function getPurposes(): BandeyriPayResponse
    {
        $purpose_request = new GetAgencyPurposesRequest($this);
        return $purpose_request->get();
    }

    public function getTransactions(): BandeyriPayResponse
    {
        $transaction_request = new GetTransactionsRequest($this);
        return $transaction_request->get();
    }

    public function paginateTransactions(int $page = 1): BandeyriPayResponse
    {
        $transaction_request = new GetTransactionsRequest($this);
        return $transaction_request->paginate($page);
    }

    public function getTransactionById(string $transaction_id): BandeyriPayResponse
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

    public function createTransaction(TransactionData $data): BandeyriPayResponse
    {
        $transaction_request = new CreateTransactionRequest($this, $data);
        return $transaction_request->create();
    }

}
