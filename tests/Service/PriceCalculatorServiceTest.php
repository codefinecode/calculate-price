<?php

// tests/Service/PriceCalculatorServiceTest.php
namespace App\Tests\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\CouponType;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\PriceCalculatorService;
use App\Service\Coupon\FixedDiscountStrategy;
use App\Service\Coupon\PercentageDiscountStrategy;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class PriceCalculatorServiceTest extends KernelTestCase
{
    /**
     * Фабрика для создания продуктов
     *
     * @param string $name
     * @param float $price
     * @return Product
     */
    private function createProduct(string $name, float $price): Product
    {
        return new Product(
            name: $name,
            price: $price,
        );
    }

    /**
     * Фабрика для создания купонов
     *
     * @param string $code
     * @param CouponType $type
     * @param float $value
     * @return Coupon
     */

    private function createCoupon(string $code, CouponType $type, float $value): Coupon
    {
        return new Coupon(
            code: $code,
            type: $type,
            value: $value,
        );
    }

    /**
     * Тестирование функционала расчета цены с учетом купона.
     *
     * @return void
     * @throws Exception
     */
    public function testCalculatePriceWithCoupon(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $productRepository = $container->get(ProductRepository::class);

        $couponRepository = $this->createMock(CouponRepository::class);
        $couponRepository->method('findOneByCode')->willReturn(
            $this->createCoupon('D15', CouponType::FIXED, 15)
        );

        $container->set(CouponRepository::class, $couponRepository);

        // Получаем сервис из контейнера
        $calculator = $container->get(PriceCalculatorService::class);

        // Ищем продукт по имени в реальной базе данных
        $product = $productRepository->findOneByName('Iphone');
        if (!$product) {
            $this->fail("Product 'Iphone' not found in the test database");
        }

        // Выполняем расчет
        $result = $calculator->calculate($product->getId(), 'DE123456789', 'D15');

        // Проверяем результат
        $this->assertEquals('Iphone', $result['product']);
        $this->assertEquals(85, $result['base_price']);
        $this->assertEquals(15, $result['discount']);
        $this->assertEqualsWithDelta(16.15, $result['tax'], 0.01, '');
        $this->assertEqualsWithDelta(101.15, $result['final_price'], 0.01, '');
    }
    /**
     * Тест для процентной скидки
     *
     * @return void
     * @throws Exception
     */
    public function testCalculatePriceWithPercentageCoupon(): void
    {
        // Создаем моки
        $productRepository = $this->createMock(ProductRepository::class);
        $couponRepository = $this->createMock(CouponRepository::class);

        // Настройка моков
        $productRepository->method('find')->willReturn($this->createProduct('Iphone', 100));
        $couponRepository->method('findOneByCode')->willReturn(
            $this->createCoupon('P10', CouponType::PERCENTAGE, 10)
        );

        // Создаем сервис
        $calculator = new PriceCalculatorService(
            fixedStrategy: new FixedDiscountStrategy(),
            percentageStrategy: new PercentageDiscountStrategy(),
            productRepository: $productRepository,
            couponRepository: $couponRepository
        );

        // Выполняем расчет
        $result = $calculator->calculate(1, 'DE123456789', 'P10');

        // Проверяем результат
        $this->assertEquals('Iphone', $result['product']);
        $this->assertEquals(90, $result['base_price']); // 100 - 10%
        $this->assertEquals(10, $result['discount'], $result['discount']);  // Скидка 10%
        $this->assertEqualsWithDelta(17.1, $result['tax'], 0.01, ''); // 90 * 19%
        $this->assertEqualsWithDelta(107.1, $result['final_price'], 0.01, ''); // 90 + 16.1
    }

    /**
     * Тест для расчета цены без купона
     *
     * @return void
     * @throws Exception
     */
    public function testCalculatePriceWithoutCoupon(): void
    {
        // Создаем моки
        $productRepository = $this->createMock(ProductRepository::class);
        $couponRepository = $this->createMock(CouponRepository::class);

        // Настройка моков
        $productRepository->method('find')->willReturn($this->createProduct('Iphone', 100));
        $couponRepository->method('findOneByCode')->willReturn(null); // Купона нет

        // Создаем сервис
        $calculator = new PriceCalculatorService(
            fixedStrategy: new FixedDiscountStrategy(),
            percentageStrategy: new PercentageDiscountStrategy(),
            productRepository: $productRepository,
            couponRepository: $couponRepository
        );

        // Выполняем расчет
        $result = $calculator->calculate(1, 'DE123456789', null);

        // Проверяем результат
        $this->assertEquals('Iphone', $result['product']);
        $this->assertEquals(100, $result['base_price']); // Без скидки
        $this->assertEquals(0, $result['discount']);     // Нет скидки
        $this->assertEqualsWithDelta(19, $result['tax'], 0.01, ''); // 100 * 19%
        $this->assertEqualsWithDelta(119, $result['final_price'], 0.01, ''); // 100 + 19
    }

    /**
     * Тест при отсутствии продукта
     *
     * @return void
     * @throws Exception
     */
    public function testCalculatePriceWithNonexistentProduct(): void
    {
        // Создаем моки
        $productRepository = $this->createMock(ProductRepository::class);
        $couponRepository = $this->createMock(CouponRepository::class);

        // Настройка моков
        $productRepository->method('find')->willReturn(null); // Продукт не найден
        $couponRepository->method('findOneByCode')->willReturn(null);

        // Создаем сервис
        $calculator = new PriceCalculatorService(
            fixedStrategy: new FixedDiscountStrategy(),
            percentageStrategy: new PercentageDiscountStrategy(),
            productRepository: $productRepository,
            couponRepository: $couponRepository
        );

        // Проверяем выбрасывание исключения
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product not found');

        $calculator->calculate(999, 'DE123456789', null); // ID продукта 999 не существует
    }

    /**
     * Тест при отсутствии купона
     *
     * @return void
     * @throws Exception
     */
    public function testCalculatePriceWithInvalidCoupon(): void
    {
        // Создаем моки
        $productRepository = $this->createMock(ProductRepository::class);
        $couponRepository = $this->createMock(CouponRepository::class);

        // Настройка моков
        $productRepository->method('find')->willReturn($this->createProduct('Iphone', 100));
        $couponRepository->method('findOneByCode')->willReturn(null); // Купона нет

        // Создаем сервис
        $calculator = new PriceCalculatorService(
            fixedStrategy: new FixedDiscountStrategy(),
            percentageStrategy: new PercentageDiscountStrategy(),
            productRepository: $productRepository,
            couponRepository: $couponRepository
        );

        // Проверяем выбрасывание исключения
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid coupon code');

        $calculator->calculate(1, 'DE123456789', 'INVALID_COUPON');
    }
}