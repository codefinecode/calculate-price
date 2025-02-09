<?php

namespace App\Service;

use App\Service\Payment\PaymentProcessorFactory;
use App\Service\PriceCalculatorService;

class PaymentService
{
    private PaymentProcessorFactory $factory;
    private PriceCalculatorService $calculator;

    public function __construct(
        PaymentProcessorFactory $factory,
        PriceCalculatorService $calculator
    ) {
        $this->factory = $factory;
        $this->calculator = $calculator;
    }

    public function processPayment(int $productId, string $taxNumber, ?string $couponCode, string $processorName): array
    {
        $priceDetails = $this->calculator->calculate($productId, $taxNumber, $couponCode);
        $processor = $this->factory->getProcessor($processorName);
        return $processor->process($priceDetails['final_price']);
    }
}