<?php

declare(strict_types=1);

namespace App\Lib\AciClient\Contract\Response\Dto;

readonly class CardDetails
{
    public function __construct(
        public string $bin,
    ) {
    }
}
