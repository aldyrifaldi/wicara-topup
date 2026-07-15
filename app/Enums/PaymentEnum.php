<?php

namespace App\Enums;

enum PaymentEnum: string
{
    case MIDTRANS = 'midtrans';
    case IPAYMU = 'ipaymu';
    case VIPAYMENT = 'vipayment';
    case DIGIFLAZZ = 'digiflazz';
    case BALANCE = 'balance';
}