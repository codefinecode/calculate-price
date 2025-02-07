<?php

namespace App\Service\Payment;

class StripePaymentProcessor implements PaymentProcessorInterface
{
    public function process(float $amount): array
    {
        // Имитация обработки платежа через Stripe
        return ['status' => 'success', 'message' => "Payment of {$amount} processed via Stripe"];
    }
}