<?php

namespace App\Service;

use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\Coupon\FixedDiscountStrategy;
use App\Service\Coupon\PercentageDiscountStrategy;

class PriceCalculatorFactory
{
    private FixedDiscountStrategy $fixedStrategy;
    private PercentageDiscountStrategy $percentageStrategy;
    private ProductRepository $productRepository;
    private CouponRepository $couponRepository;

    public function __construct(
        FixedDiscountStrategy $fixedStrategy,
        PercentageDiscountStrategy $percentageStrategy,
        ProductRepository $productRepository,
        CouponRepository $couponRepository
    ) {
        $this->fixedStrategy = $fixedStrategy;
        $this->percentageStrategy = $percentageStrategy;
        $this->productRepository = $productRepository;
        $this->couponRepository = $couponRepository;
    }

    public function create(): PriceCalculatorService
    {
        return new PriceCalculatorService(
            $this->fixedStrategy,
            $this->percentageStrategy,
            $this->productRepository,
            $this->couponRepository
        );
    }
}