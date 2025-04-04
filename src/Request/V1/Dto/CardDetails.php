<?php

declare(strict_types=1);

namespace App\Request\V1\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CardDetails
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Luhn]
        #[Assert\Length(16)]
        public string $number,
        #[Assert\Regex('/^[0-9]{4}$/')]
        #[Assert\NotBlank]
        public string $expYear,
        #[Assert\NotBlank]
        #[Assert\Regex('/^(0[1-9]|1[0-2])$/')]
        public string $expMonth,
        #[Assert\NotBlank]
        #[Assert\Regex('/^[0-9]{3}$/')]
        public string $cvv,
    ) {
    }
}
