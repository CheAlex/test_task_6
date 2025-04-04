<?php

declare(strict_types=1);

namespace App\Service\PaymentService;

use App\Request\V1\Payment\CreatePaymentRequest;

readonly class CreatePaymentData
{
    public function __construct(
        public string $amount,
        public string $currency,
        public CardDetailsData $card,
    ) {
    }

    public static function createRequest(CreatePaymentRequest $request): self
    {
        return new self(
            amount: $request->amount,
            currency: $request->currency,
            card: new CardDetailsData(
                number: $request->card->number,
                expYear: $request->card->expYear,
                expMonth: $request->card->expMonth,
                cvv: $request->card->cvv,
            ),
        );
    }
}
