<?php

namespace App\Service\Payment;

class PaypalPaymentProcessor implements PaymentProcessorInterface
{
    public function process(float $amount): array
    {
        // Имитация обработки платежа через PayPal
        return ['status' => 'success', 'message' => "Payment of {$amount} processed via PayPal"];
    }
}