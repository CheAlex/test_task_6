<?php

declare(strict_types=1);

namespace App\Lib\Shift4Client\Contract\Request;

readonly class CreateChargeRequest
{
    public function __construct(
        public int $amount,
        public string $currency,
        public CreateCardRequest $card,
    ) {
    }
}
