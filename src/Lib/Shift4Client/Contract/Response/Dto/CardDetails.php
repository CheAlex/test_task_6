<?php

declare(strict_types=1);

namespace App\Lib\Shift4Client\Contract\Response\Dto;

readonly class CardDetails
{
    public function __construct(
        public string $first6,
    ) {
    }
}
