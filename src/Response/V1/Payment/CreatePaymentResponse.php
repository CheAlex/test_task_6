<?php

declare(strict_types=1);

namespace App\Response\V1\Payment;

use App\Service\PaymentService\CreatePaymentResult;

readonly class CreatePaymentResponse
{
    public function __construct(
        public string $transactionId,
        public string $createdAt,
        public string $amount,
        public string $currency,
        public string $cardBin,
    ) {
    }

    public static function create(CreatePaymentResult $createPaymentResult): self
    {
        return new self(
            transactionId: $createPaymentResult->transactionId,
            createdAt: $createPaymentResult->createdAt->format(\DateTimeImmutable::ATOM),
            amount: $createPaymentResult->amount,
            currency: $createPaymentResult->currency,
            cardBin: $createPaymentResult->cardBin,
        );
    }
}
