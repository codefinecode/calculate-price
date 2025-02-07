<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Dto\PurchaseInput;
use App\Service\PaymentService;

class PurchaseController extends AbstractController
{
    #[Route('/purchase', methods: ['POST'])]
    public function purchase(
        Request            $request,
        ValidatorInterface $validator,
        PaymentService     $paymentService
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $input = new PurchaseInput(
            $data['product'] ?? null,
            $data['taxNumber'] ?? null,
            $data['couponCode'] ?? null,
            $data['paymentProcessor'] ?? null
        );

        $violations = $validator->validate($input);

        if (count($violations) > 0) {
            return new JsonResponse(['errors' => (string)$violations], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $result = $paymentService->processPayment(
                $input->getProduct(),
                $input->getTaxNumber(),
                $input->getCouponCode(),
                $input->getPaymentProcessor()
            );
            return new JsonResponse($result);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}