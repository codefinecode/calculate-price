<?php

namespace App\Enum;

enum CouponType: string
{
    case FIXED = 'fixed';
    case PERCENTAGE = 'percentage';
}
