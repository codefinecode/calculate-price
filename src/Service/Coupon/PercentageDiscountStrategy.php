<?php

namespace App\Service\Coupon;

class PercentageDiscountStrategy implements DiscountStrategyInterface
{
    public function apply(float $price, float $value): float
    {
        return $price * (1 - $value / 100);
    }
}