<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Enum\CouponType;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\Coupon\DiscountStrategyInterface;
use InvalidArgumentException;

class PriceCalculatorService
{
    private array $taxRates = [
        'DE' => 0.19,
        'IT' => 0.22,
        'FR' => 0.20,
        'GR' => 0.24,
    ];

    public function __construct(
        private readonly DiscountStrategyInterface $fixedStrategy,
        private readonly DiscountStrategyInterface $percentageStrategy,
        private readonly ProductRepository         $productRepository,
        private readonly CouponRepository          $couponRepository
    )
    {
    }

    public function calculate(int $productId, string $taxNumber, ?string $couponCode): array
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new InvalidArgumentException('Product not found');
        }
        $price = $product->getPrice();

        /** @var Coupon|null $coupon */
        $coupon = null;

        // Применяем купон (если есть)
        if ($couponCode) {
            $coupon = $this->couponRepository->findOneByCode($couponCode);
            if (!$coupon) {
                throw new InvalidArgumentException('Invalid coupon code');
            }

            $strategy = match ($coupon->getType()) {
                CouponType::FIXED => $this->fixedStrategy,
                CouponType::PERCENTAGE => $this->percentageStrategy,
            };

            $price = $strategy->apply($price, $coupon->getValue());
        }

        // Расчет налога
        $countryCode = substr($taxNumber, 0, 2);
        $taxRate = $this->taxRates[$countryCode] ?? 0;
        $tax = $price * $taxRate;
        $finalPrice = $price + $tax;

        return [
            'product' => $product->getName(),
            'base_price' => $price,
            'discount' => $coupon ? $coupon->getValue() : 0,
            'tax' => $tax,
            'final_price' => $finalPrice,
        ];
    }
}