<?php

namespace Javaabu\BandeyriPay\Enums;

enum TransactionStates: string
{
    case NEW = 'NEW';
    case PP_TRANSACTION_GENERATED = 'PP_TRANSACTION_GENERATED';
    case CONFIRMED = 'CONFIRMED';
    case CANCELLED = 'CANCELLED';


    public function getDescription(): string
    {
        return match ($this->value) {
            self::NEW->value => 'Initial state',
            self::PP_TRANSACTION_GENERATED->value => 'Transaction generated by payment provider',
            self::CONFIRMED->value => 'Transaction confirmed',
            self::CANCELLED->value => 'Transaction cancelled',
            default => 'Unknown state',
        };
    }

}
