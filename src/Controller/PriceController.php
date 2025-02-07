<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Dto\CalculatePriceInput;
use App\Service\PriceCalculatorService;

class PriceController extends AbstractController
{
    #[Route('/calculate-price', methods: ['POST'])]
    public function calculatePrice(
        Request                $request,
        ValidatorInterface     $validator,
        PriceCalculatorService $calculator
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $input = new CalculatePriceInput(
            $data['product'] ?? null,
            $data['taxNumber'] ?? null,
            $data['couponCode'] ?? null
        );

        $violations = $validator->validate($input);

        if (count($violations) > 0) {
            return new JsonResponse(['errors' => (string)$violations], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $result = $calculator->calculate(
                $input->getProduct(),
                $input->getTaxNumber(),
                $input->getCouponCode()
            );
            return new JsonResponse($result);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}