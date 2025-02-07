<?php

namespace App\Service\Payment;

use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapter implements PaymentProcessorInterface
{
    private StripePaymentProcessor $processor;

    public function __construct(StripePaymentProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function process(float $amount): array
    {
        $result = $this->processor->processPayment($amount);
        if ($result) {
            return ['status' => 'success', 'message' => "Payment of {$amount} processed via Stripe"];
        }

        return ['status' => 'error', 'message' => 'Payment failed'];
    }
}