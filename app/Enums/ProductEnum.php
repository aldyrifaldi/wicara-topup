<?php

namespace App\Enums;

enum ProductEnum: string
{
    // Product Types
    case TYPE_PRODUCT = 'product';
    case TYPE_JOKI = 'joki';

    // Product Status
    case STATUS_ACTIVE = 'active';
    case STATUS_INACTIVE = 'inactive';
}