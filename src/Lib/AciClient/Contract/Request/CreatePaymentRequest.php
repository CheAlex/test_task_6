<?php

declare(strict_types=1);

namespace App\Lib\AciClient\Contract\Request;

use App\Lib\AciClient\Contract\Request\Dto\CardDetails;

readonly class CreatePaymentRequest
{
    public function __construct(
        public string $amount,
        public string $currency,
        public CardDetails $card,
        public string $entityId = '8ac7a4c79394bdc801939736f17e063d',
        public string $paymentBrand = 'VISA',
        public string $paymentType = 'DB',
    ) {
    }
}
