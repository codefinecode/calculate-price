<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

class PurchaseInput
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private int $product;

    #[CustomAssert\TaxNumber]
    private string $taxNumber;

    #[CustomAssert\Coupon]
    private ?string $couponCode;

    #[CustomAssert\PaymentProcessor]
    #[Assert\NotBlank]
    private string $paymentProcessor;

    public function __construct(int $product, string $taxNumber, ?string $couponCode, string $paymentProcessor)
    {
        $this->product = $product;
        $this->taxNumber = $taxNumber;
        $this->couponCode = $couponCode;
        $this->paymentProcessor = $paymentProcessor;
    }

    public function getProduct(): int
    {
        return $this->product;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }
}