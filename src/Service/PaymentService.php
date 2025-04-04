<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\PaymentGateway\PaymentGatewayNotFoundException;
use App\Service\PaymentGateway\PaymentGatewayLocator;
use App\Service\PaymentService\CreatePaymentData;
use App\Service\PaymentService\CreatePaymentResult;

readonly class PaymentService
{
    public function __construct(
        private PaymentGatewayLocator $paymentGatewayLocator,
    ) {
    }

    /**
     * @throws PaymentGatewayNotFoundException
     */
    public function createPayment(
        string $paymentGatewaySlug,
        CreatePaymentData $createPaymentData
    ): CreatePaymentResult {
        $paymentGateway = $this->paymentGatewayLocator->get($paymentGatewaySlug);
        $createPaymentResult = $paymentGateway->createPayment($createPaymentData);

        return new CreatePaymentResult(
            transactionId: $createPaymentResult->transactionId,
            createdAt: $createPaymentResult->createdAt,
            amount: $createPaymentData->amount,
            currency: $createPaymentData->currency,
            cardBin: $createPaymentResult->cardBin,
        );
    }
}
