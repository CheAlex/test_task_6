<?php

declare(strict_types=1);

namespace App\Controller\V1\Payment;

use App\Exception\Http\ValidationConstraintViolationException;
use App\Exception\PaymentGateway\PaymentGatewayNotFoundException;
use App\Request\V1\Payment\CreatePaymentRequest;
use App\Response\V1\Payment\CreatePaymentResponse;
use App\Service\PaymentService;
use App\Service\PaymentService\CreatePaymentData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;

#[Route('/v1/payment/{paymentGatewaySlug}', methods: ['POST'])]
class CreatePaymentController extends AbstractController
{
    public function __invoke(
        string $paymentGatewaySlug,
        #[MapRequestPayload] CreatePaymentRequest $createPaymentRequest,
        PaymentService $paymentService,
    ): JsonResponse {
        try {
            $createPaymentResult = $paymentService->createPayment(
                $paymentGatewaySlug,
                CreatePaymentData::createRequest($createPaymentRequest)
            );
        } catch (PaymentGatewayNotFoundException $exception) {
            throw ValidationConstraintViolationException::create(
                new ConstraintViolation($exception->getMessage(), null, [], null, null, null)
            );
        }

        $createPaymentResponse = CreatePaymentResponse::create($createPaymentResult);

        return new JsonResponse($createPaymentResponse);
    }
}
