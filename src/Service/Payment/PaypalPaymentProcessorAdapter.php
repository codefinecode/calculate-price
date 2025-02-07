<?php

namespace App\Service\Payment;

use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Exception;

class PaypalPaymentProcessorAdapter implements PaymentProcessorInterface
{
    private PaypalPaymentProcessor $processor;

    public function __construct(PaypalPaymentProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function process(float $amount): array
    {
        try {
            // Переводим сумму в копейки/центы (предполагается, что цена передается в евро)
            $this->processor->pay((int) ($amount * 100));
            return ['status' => 'success', 'message' => "Payment of {$amount} processed via PayPal"];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}