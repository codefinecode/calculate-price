<?php

namespace App\Service;

use App\Service\Payment\PaymentProcessorFactory;

class PaymentService
{
    private PaymentProcessorFactory $factory;
    private PriceCalculatorFactory $calculatorFactory;

    public function __construct(
        PaymentProcessorFactory $factory,
        PriceCalculatorFactory $calculatorFactory
    ) {
        $this->factory = $factory;
        $this->calculatorFactory = $calculatorFactory;
    }

    public function processPayment(int $productId, string $taxNumber, ?string $couponCode, string $processorName): array
    {
        $calculator = $this->calculatorFactory->create();
        $priceDetails = $calculator->calculate($productId, $taxNumber, $couponCode);

        $processor = $this->factory->getProcessor($processorName);
        return $processor->process($priceDetails['final_price']);
    }
}