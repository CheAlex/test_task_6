<?php

declare(strict_types=1);

namespace App\Lib\AciClient\Contract\Response;

use App\Lib\AciClient\Contract\Response\Dto\CardDetails;

readonly class CreatePaymentResponse
{
    public function __construct(
        public string $id,
        public \DateTimeImmutable $timestamp,
        public CardDetails $card,
    ) {
    }
}
