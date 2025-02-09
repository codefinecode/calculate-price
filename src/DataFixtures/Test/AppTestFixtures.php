<?php

namespace App\DataFixtures\Test;

use App\DataFixtures\AppFixtures;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\CouponType;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AppTestFixtures extends AppFixtures implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['test'];
    }

    protected function addTestData(ObjectManager $manager): void
    {

        // Добавляем купон на 6% скидку
        $coupon = new Coupon(
            code:'TEST_6PERCENT',
            type: CouponType::PERCENTAGE,
            value: 6,
        );
        $manager->persist($coupon);

    }

    public function load(ObjectManager $manager): void
    {
        parent::load($manager);
        $this->addTestData($manager);
        $manager->flush();
    }
}