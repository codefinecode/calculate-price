<?php

namespace App\DataFixtures;

use App\Enum\CouponType;
use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['dev'];
    }
    public function load(ObjectManager $manager): void
    {
        // Добавляем продукты
        $products = [
            ['name' => 'Iphone', 'price' => 100],
            ['name' => 'Наушники', 'price' => 20],
            ['name' => 'Чехол', 'price' => 10],
        ];

        foreach ($products as $productData) {
            $product = new Product(
                name: $productData['name'],
                price: $productData['price']
            );
            $manager->persist($product);
        }

        // Добавляем купоны
        $coupons = [
            ['code' => 'D15', 'type' => CouponType::FIXED, 'value' => 15],
            ['code' => 'P10', 'type' => CouponType::PERCENTAGE, 'value' => 10],
        ];

        foreach ($coupons as $couponData) {
            $coupon = new Coupon(
                code: $couponData['code'],
                type: $couponData['type'],
                value: $couponData['value']
            );
            $manager->persist($coupon);
        }

        $manager->flush();
    }
}