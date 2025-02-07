<?php

namespace App\Service\Coupon;

interface DiscountStrategyInterface
{
    public function apply(float $price, float $value): float;
}