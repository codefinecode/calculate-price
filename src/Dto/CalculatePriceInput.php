<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

class CalculatePriceInput
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private int $product;

    #[CustomAssert\TaxNumber]
    private string $taxNumber;

    #[CustomAssert\Coupon]
    private ?string $couponCode = null;

    public function __construct(int $product, string $taxNumber, ?string $couponCode = null)
    {
        $this->product = $product;
        $this->taxNumber = $taxNumber;
        $this->couponCode = $couponCode;
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
}