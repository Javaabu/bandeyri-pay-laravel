<?php

namespace Javaabu\BandeyriGateway\Enums;

enum TransactionTypes: string
{
    case ONLINE = 'online';
    case PREPAYMENT = 'prepayment';
    case CASH = 'cash';
    case POS_TERMINAL = 'pos-terminal';
    case RTGS = 'rtgs';

}
