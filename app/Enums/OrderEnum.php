<?php

namespace App\Enums;

enum OrderEnum: string
{
    // Order Types
    case TYPE_PRODUCT = 'product';
    case TYPE_JOKI = 'joki';

    // Order Status
    case STATUS_PENDING = 'pending';
    case STATUS_PROCESSING = 'processing';
    case STATUS_COMPLETED = 'completed';
    case STATUS_FAILED = 'failed';
    case STATUS_CANCELLED = 'cancelled';

    // Payment Status
    case PAYMENT_STATUS_PENDING = 'pending';
    case PAYMENT_STATUS_PAID = 'paid';
    case PAYMENT_STATUS_FAILED = 'failed';
    case PAYMENT_STATUS_REFUNDED = 'refunded';

    // Payment Methods
    case PAYMENT_METHOD_MIDTRANS = 'midtrans';
    case PAYMENT_METHOD_IPAYMU = 'ipaymu';
    case PAYMENT_METHOD_VIPAYMENT = 'vipayment';
    case PAYMENT_METHOD_DIGIFLAZZ = 'digiflazz';
    case PAYMENT_METHOD_BALANCE = 'balance';
}