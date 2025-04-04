<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\PaymentGateway\Dto\CreatePaymentResult;
use App\Service\PaymentGateway\PaymentGatewayInterface;
use App\Service\PaymentGateway\PaymentGatewayLocator;
use App\Service\PaymentService;
use App\Service\PaymentService\CardDetailsData;
use App\Service\PaymentService\CreatePaymentData;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    private readonly PaymentGatewayLocator&MockObject $paymentGatewayLocator;
    private readonly PaymentService $paymentService;

    protected function setUp(): void
    {
        $this->paymentGatewayLocator = $this->createMock(PaymentGatewayLocator::class);
        $this->paymentService = new PaymentService($this->paymentGatewayLocator);
    }

    public function testCreatePayment(): void
    {
        $paymentGatewaySlug = 'slug1';
        $createPaymentData = new CreatePaymentData(
            amount: '12.34',
            currency: 'USD',
            card: new CardDetailsData(
                number: '4242424242424242',
                expYear: '2025',
                expMonth: '12',
                cvv: '123'
            )
        );
        $createPaymentResult = new CreatePaymentResult(
            transactionId: 'char_T7QKaNRQIHgQCYRlL0N04H4R',
            createdAt: new \DateTimeImmutable(),
            cardBin: '424242'
        );

        $paymentGateway = $this->createMock(PaymentGatewayInterface::class);
        $paymentGateway
            ->expects($this->once())
            ->method('createPayment')
            ->with($createPaymentData)
            ->willReturn($createPaymentResult)
        ;

        $this->paymentGatewayLocator
            ->expects($this->once())
            ->method('get')
            ->with($paymentGatewaySlug)
            ->willReturn($paymentGateway)
        ;

        $result = $this->paymentService->createPayment($paymentGatewaySlug, $createPaymentData);

        self::assertSame($createPaymentResult->transactionId, $result->transactionId);
        self::assertSame($createPaymentResult->createdAt, $result->createdAt);
        self::assertSame($createPaymentData->amount, $result->amount);
        self::assertSame($createPaymentData->currency, $result->currency);
        self::assertSame($createPaymentResult->cardBin, $result->cardBin);
    }
}
