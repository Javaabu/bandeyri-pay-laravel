<?php

namespace Javaabu\BandeyriPay\Enums;

enum CustomerTypes: string
{
    case INDIVIDUAL_LOCAL = 'Individual Local';
    case INDIVIDUAL_FOREIGN = 'Individual Foreign';
    case ORGANIZATION_LOCAL = 'Organization Local';
    case ORGANIZATION_FOREIGN = 'Organization Foreign';
}
