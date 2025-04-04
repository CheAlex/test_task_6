<?php

declare(strict_types=1);

namespace App\Service\PaymentGateway;

use App\Service\PaymentGateway\Dto\CreatePaymentResult;
use App\Service\PaymentService\CreatePaymentData;

interface PaymentGatewayInterface
{
    public function createPayment(CreatePaymentData $createPaymentData): CreatePaymentResult;
}
