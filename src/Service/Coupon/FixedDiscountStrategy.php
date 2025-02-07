<?php

namespace App\Service\Coupon;

class FixedDiscountStrategy implements DiscountStrategyInterface
{
    public function apply(float $price, float $value): float
    {
        return max(0, $price - $value);
    }
}