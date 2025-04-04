<?php

declare(strict_types=1);

namespace App\Request\V1\Payment;

use App\Request\V1\Dto\CardDetails;
use App\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\GroupSequence(['CreatePaymentRequest', 'Strict'])]
readonly class CreatePaymentRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Sequentially(
            constraints: [
                new AppAssert\Money\AmountValid(currency: 'currency'),
                new AppAssert\Money\AmountGreaterThan(currency: 'currency', value: 0, message: 'Invalid amount'),
            ],
            groups: ['Strict']
        )]
        public string $amount,
        #[Assert\NotBlank]
        #[AppAssert\Money\CurrencyExists]
        public string $currency,
        #[Assert\NotNull]
        #[Assert\Valid]
        public CardDetails $card,
    ) {
    }
}
