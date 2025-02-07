<?php

namespace App\Service\Payment;

interface PaymentProcessorInterface
{
    public function process(float $amount): array;
}