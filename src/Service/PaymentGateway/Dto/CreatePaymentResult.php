<?php

declare(strict_types=1);

namespace App\Service\PaymentGateway\Dto;

readonly class CreatePaymentResult
{
    public function __construct(
        public string $transactionId,
        public \DateTimeImmutable $createdAt,
        public string $cardBin,
    ) {
    }
}
