<?php

declare(strict_types=1);

namespace App\Service\PaymentGateway;

use App\Lib\Shift4Client\Contract\Request\CreateCardRequest;
use App\Lib\Shift4Client\Contract\Request\CreateChargeRequest;
use App\Lib\Shift4Client\Shift4Client;
use App\Service\PaymentGateway\Dto\CreatePaymentResult;
use App\Service\PaymentService\CreatePaymentData;
use Money\Currency;
use Money\MoneyParser;

readonly class Shift4PaymentGateway implements PaymentGatewayInterface
{
    public function __construct(
        private Shift4Client $shift4Client,
        private MoneyParser $moneyParser,
    ) {
    }

    public function createPayment(CreatePaymentData $createPaymentData): CreatePaymentResult
    {
        $amountVo = $this->moneyParser->parse($createPaymentData->amount, new Currency($createPaymentData->currency));

        $createChargeRequest = new CreateChargeRequest(
            amount: (int) $amountVo->getAmount(),
            currency: $createPaymentData->currency,
            card: new CreateCardRequest(
                number: $createPaymentData->card->number,
                expYear: $createPaymentData->card->expYear,
                expMonth: $createPaymentData->card->expMonth,
                cvc: $createPaymentData->card->cvv,
            )
        );

        $createChargeResponse = $this->shift4Client->createCharge($createChargeRequest);

        return new CreatePaymentResult(
            transactionId: $createChargeResponse->id,
            createdAt: $createChargeResponse->created,
            cardBin: $createChargeResponse->card->first6,
        );
    }
}
