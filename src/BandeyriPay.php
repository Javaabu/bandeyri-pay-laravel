<?php

namespace Javaabu\BandeyriPay;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Javaabu\BandeyriPay\Exceptions\InvalidConfiguration;
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
    private ?Carbon $expires_at;

    /**
     * @throws InvalidConfiguration
     */
    public function __construct()
    {
        $this->client_id = config('bandeyri-pay.bandeyri_client_id') ?? throw new InvalidConfiguration("Bandeyri API URL is not configured");
        $this->client_secret = config('bandeyri-pay.bandeyri_client_secret') ?? throw new InvalidConfiguration("Bandeyri Client ID is not configured");
        $this->api_url = config('bandeyri-pay.bandeyri_api_url') ?? throw new InvalidConfiguration("Bandeyri Client Secret is not configured");
        $this->bearer_token = Cache::get('bandeyri_access_token');
        $this->expires_at = Cache::get('bandeyri_access_token_expires_at');
    }

    /**
     * @throws Unauthorized
     */
    private function authenticate(): void
    {
        $token_url = $this->api_url . '/token';
        $response = Http::post($token_url, [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $expires_at = Carbon::now()->addSeconds(data_get($data, 'expires_in'));
            $this->bearer_token = data_get($data, 'access_token');
            $this->expires_at = $expires_at;

            // Cache the token and expiry time
            Cache::put('bandeyri_access_token', $this->bearer_token, $expires_at);
            Cache::put('bandeyri_access_token_expires_at', $expires_at, $expires_at);
        } else {
            throw new Unauthorized("Failed to authenticate with Bandeyri API");
        }
    }

    public function setBearerToken(string $bearer_token): void
    {
        $this->bearer_token = $bearer_token;
    }

    public function setExpiresAt(Carbon $expires_at): void
    {
        $this->expires_at = $expires_at;
    }

    public function getApiUrl(): string
    {
        return $this->api_url;
    }

    /**
     * @throws Unauthorized
     */
    public function getHeaders(): array
    {
        $bearer_token = $this->getBearerToken();
        return [
            'authorization' => "Bearer $bearer_token",
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'x-bpg-api' => 'v1',
        ];
    }

    /**
     * @throws Unauthorized
     */
    public function getBearerToken(): ?string
    {
        if (!$this->bearer_token || !$this->expires_at || now()->greaterThanOrEqualTo($this->expires_at)) {
            $this->authenticate();
        }

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

    public function getTransactions(?int $page = null): BandeyriPayResponse
    {
        $transaction_request = new GetTransactionsRequest($this);
        if ($page) {
            return $transaction_request->paginate($page);
        }

        return $transaction_request->get();
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
