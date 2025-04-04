<?php

declare(strict_types=1);

namespace App\Service\PaymentGateway;

use App\Lib\AciClient\AciClient;
use App\Lib\AciClient\Contract\Request\CreatePaymentRequest as AciCreatePaymentRequest;
use App\Lib\AciClient\Contract\Request\Dto\CardDetails;
use App\Service\PaymentGateway\Dto\CreatePaymentResult;
use App\Service\PaymentService\CreatePaymentData;

readonly class AciPaymentGateway implements PaymentGatewayInterface
{
    public function __construct(
        private AciClient $aciClient,
    ) {
    }

    public function createPayment(CreatePaymentData $createPaymentData): CreatePaymentResult
    {
        $createChargeRequest = new AciCreatePaymentRequest(
            amount: $createPaymentData->amount,
            currency: $createPaymentData->currency,
            card: new CardDetails(
                number: $createPaymentData->card->number,
                expiryYear: $createPaymentData->card->expYear,
                expiryMonth: $createPaymentData->card->expMonth,
                cvv: $createPaymentData->card->cvv,
            ),
        );

        $createChargeResponse = $this->aciClient->createPayment($createChargeRequest);

        return new CreatePaymentResult(
            transactionId: $createChargeResponse->id,
            createdAt: $createChargeResponse->timestamp,
            cardBin: $createChargeResponse->card->bin,
        );
    }
}
