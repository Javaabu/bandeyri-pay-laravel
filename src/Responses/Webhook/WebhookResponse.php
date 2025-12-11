<?php

namespace Javaabu\BandeyriPay\Responses\Webhook;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Javaabu\BandeyriPay\Contracts\ResponseContract;
use Javaabu\BandeyriPay\Enums\TransactionStates;

class WebhookResponse implements ResponseContract
{

    public function __construct(
        public readonly ?string $type,
        public readonly ?string $created_at,
        public readonly ?Carbon $formatted_created_at,
        public readonly ?string $id,
        public readonly ?string $local_id,
        public readonly ?string $customer_reference,
        public readonly ?TransactionStates $state,
        private readonly ?string $signature,
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        $signature = $request->header('x-bpg-signature');

        return new self (
            type: $request->input('type'),
            created_at: $request->input('created_at'),
            formatted_created_at: $request->input('created_at') ? Carbon::parse($request->input('created_at')) : null,
            // id is inside data array
            id: $request->input('data.id'),
            local_id: $request->input('data.local_id'),
            customer_reference: $request->input('data.customer_reference'),
            state: $request->input('data.state') ? TransactionStates::tryFrom($request->input('data.state')) : null,
            signature: $signature,
        );
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function webhookIsExpired(): bool
    {
        if (!$this->created_at) {
            return true;
        }

        return $this->formatted_created_at->diffInMinutes(now()) > 1;
    }
}
