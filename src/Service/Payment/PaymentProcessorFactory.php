<?php

namespace App\Service\Payment;

class PaymentProcessorFactory
{
    private array $processors = [];

    public function __construct(
        PaymentProcessorInterface $paypal,
        PaymentProcessorInterface $stripe
    )
    {
        // Используем DI для инжекции платежных процессоров, что позволит легко добавить новыме процессоры.
        $this->processors['paypal'] = $paypal;
        $this->processors['stripe'] = $stripe;
    }

    public function getProcessor(string $processorName): PaymentProcessorInterface
    {
        if (!isset($this->processors[$processorName])) {
            throw new \InvalidArgumentException('Invalid payment processor');
        }

        return $this->processors[$processorName];
    }
}