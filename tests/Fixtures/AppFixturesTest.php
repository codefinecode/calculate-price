<?php

namespace App\Tests\Fixtures;

use App\DataFixtures\Test\AppTestFixtures;
use Doctrine\Bundle\DoctrineBundle;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppFixturesTest extends KernelTestCase
{
    public function testFixturesLoad(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        // Очищаем базу данных перед загрузкой фикстур
        $purger = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);

        // Загружаем тестовые фикстуры (базовая+)
        $executor->execute([new AppTestFixtures()]);

        // Проверяем наличие данных
        $productRepository = $entityManager->getRepository('App\Entity\Product');
        $this->assertGreaterThan(0, $productRepository->count([]));

        $couponRepository = $entityManager->getRepository('App\Entity\Coupon');
        $this->assertGreaterThan(0, $couponRepository->count([]));
    }
}