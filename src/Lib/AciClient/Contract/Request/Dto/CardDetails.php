<?php

declare(strict_types=1);

namespace App\Lib\AciClient\Contract\Request\Dto;

readonly class CardDetails
{
    public function __construct(
        public string $number,
        public string $expiryYear,
        public string $expiryMonth,
        public string $cvv,
        public string $holder = 'Jane Jones',
    ) {
    }
}
