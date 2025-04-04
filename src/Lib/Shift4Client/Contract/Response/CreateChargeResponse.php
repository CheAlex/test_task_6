<?php

declare(strict_types=1);

namespace App\Lib\Shift4Client\Contract\Response;

use App\Lib\Shift4Client\Contract\Response\Dto\CardDetails;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

readonly class CreateChargeResponse
{
    public function __construct(
        public string $id,
        #[Context(context: [DateTimeNormalizer::FORMAT_KEY => 'U'])]
        public \DateTimeImmutable $created,
        public CardDetails $card,
    ) {
    }
}
