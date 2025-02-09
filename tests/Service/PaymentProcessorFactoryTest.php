<?php

// tests/Service/PaymentProcessorFactoryTest.php
namespace App\Tests\Service;

use App\Service\Payment\PaymentProcessorFactory;
use App\Service\Payment\PaypalPaymentProcessorAdapter;
use App\Service\Payment\StripePaymentProcessorAdapter;
use App\Service\Payment\PaymentProcessorInterface;
use PHPUnit\Framework\TestCase;

class PaymentProcessorFactoryTest extends TestCase
{
    /**
     * Тестирование фабрики платежных процессоров
     *
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetProcessor(): void
    {
        // Создаем адаптеры из неизменяемых (по условиям ТЗ) классов
        $paypalAdapter = new PaypalPaymentProcessorAdapter(
            $this->createMock(\Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor::class)
        );

        $stripeAdapter = new StripePaymentProcessorAdapter(
            $this->createMock(\Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor::class)
        );

        // Создаем фабрику
        $factory = new PaymentProcessorFactory($paypalAdapter, $stripeAdapter);

        // Проверяем получение процессоров
        $this->assertInstanceOf(PaypalPaymentProcessorAdapter::class, $factory->getProcessor('paypal'));
        $this->assertInstanceOf(StripePaymentProcessorAdapter::class, $factory->getProcessor('stripe'));

        // Проверяем обработку неверного имени процессора
        $this->expectException(\InvalidArgumentException::class);
        $factory->getProcessor('unknown');
    }
}