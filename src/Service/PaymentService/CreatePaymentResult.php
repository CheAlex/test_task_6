<?php

declare(strict_types=1);

namespace App\Service\PaymentService;

readonly class CreatePaymentResult
{
    public function __construct(
        public string $transactionId,
        public \DateTimeImmutable $createdAt,
        public string $amount,
        public string $currency,
        public string $cardBin,
    ) {
    }
}
