<?php

namespace App\Service;

use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\Payment\PaymentProcessorFactory;
use App\Service\Coupon\FixedDiscountStrategy;
use App\Service\Coupon\PercentageDiscountStrategy;

class PaymentService
{
    private PaymentProcessorFactory $factory;
    private ProductRepository $productRepository;
    private CouponRepository $couponRepository;

    public function __construct(
        PaymentProcessorFactory $factory,
        ProductRepository $productRepository,
        CouponRepository $couponRepository
    ) {
        $this->factory = $factory;
        $this->productRepository = $productRepository;
        $this->couponRepository = $couponRepository;
    }

    public function processPayment(int $productId, string $taxNumber, ?string $couponCode, string $processorName): array
    {
        // Рассчитываем цену
        $calculator = new PriceCalculatorService(
            new FixedDiscountStrategy(),
            new PercentageDiscountStrategy(),
            $this->productRepository,
            $this->couponRepository
        );

        $priceDetails = $calculator->calculate($productId, $taxNumber, $couponCode);

        // Выбираем платежный процессор
        $processor = $this->factory->getProcessor($processorName);

        // Обрабатываем платеж
        return $processor->process($priceDetails['final_price']);
    }
}