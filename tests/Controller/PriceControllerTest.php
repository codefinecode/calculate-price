<?php

// tests/Controller/PriceControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PriceControllerTest extends WebTestCase
{

    private function getCalculatePriceResponse($data): \Symfony\Component\HttpFoundation\Response
    {
        $client = static::createClient();
        $client->request('POST', '/calculate-price', [], [], [], json_encode($data));
        return $client->getResponse();
    }

    public function testCalculatePriceWithTaxOnly(): void
    {
        $response = $this->getCalculatePriceResponse([
            'product' => 1, // Iphone
            'taxNumber' => 'GR123456789',
            'couponCode' => null,
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Iphone', $data['product']);
        $this->assertEquals(100, $data['base_price']);
        $this->assertEquals(0, $data['discount']);
        $this->assertEquals(24, $data['tax']);
        $this->assertEquals(124, $data['final_price']);
    }

    public function testCalculatePriceWithTaxAndDiscount(): void
    {
        // Проверяем расчет цены для Греции (налог 24%) с купоном на 6% скидки
        $response = $this->getCalculatePriceResponse([
            'product' => 1, // Iphone
            'taxNumber' => 'GR123456789',
            'couponCode' => 'TEST_6PERCENT',
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Iphone', $data['product']);
        $this->assertEquals(94, $data['base_price']); // 100 - 6%
        $this->assertEquals(6, $data['discount']);   // 6% от 100
        $this->assertEqualsWithDelta(22.56, $data['tax'], 0.01, ''); // 94 * 24%(GR)
        $this->assertEqualsWithDelta(116.56, $data['final_price'], 0.01, ''); // 94 + 22.56
    }
}