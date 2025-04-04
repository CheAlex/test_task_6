<?php

declare(strict_types=1);

namespace App\Service\PaymentService;

readonly class CardDetailsData
{
    public function __construct(
        public string $number,
        public string $expYear,
        public string $expMonth,
        public string $cvv,
    ) {
    }
}
