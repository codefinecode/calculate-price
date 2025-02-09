<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{

    private function getPurchaseResponse($data): \Symfony\Component\HttpFoundation\Response
    {
        $client = static::createClient();
        $client->request('POST', '/purchase', [], [], [], json_encode($data));
        return $client->getResponse();
    }

    public function testPurchaseWithPayPal(): void
    {
        $response = $this->getPurchaseResponse([
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'D15',
            'paymentProcessor' => 'paypal',
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals('success', $data['status']);
        $this->assertStringContainsString('processed via PayPal', $data['message']);
    }

    public function testPurchaseWithStripe(): void
    {
        $response = $this->getPurchaseResponse([
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'D15',
            'paymentProcessor' => 'stripe',
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals('success', $data['status']);
        $this->assertStringContainsString('processed via Stripe', $data['message']);
    }
}