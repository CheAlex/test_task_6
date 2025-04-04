<?php

declare(strict_types=1);

namespace App\Lib\Shift4Client\Contract\Request;

readonly class CreateCardRequest
{
    public function __construct(
        public string $number,
        public string $expYear,
        public string $expMonth,
        public string $cvc,
    ) {
    }
}
