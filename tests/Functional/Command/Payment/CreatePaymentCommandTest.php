<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command\Payment;

use Http\Mock\Client;
use Nyholm\Psr7\Response as PsrResponse;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreatePaymentCommandTest extends KernelTestCase
{
    private const string SUCCESS_RESPONSE = <<<STR
{
  "id" : "char_Zq8xv5DJDgbjgLYpUFCb9WSj",
  "created" : 1743725079,
  "objectType" : "charge",
  "merchant" : "mrc_BXbWziIT2eyXEszaPx6KK59O",
  "amount" : 1000,
  "amountRefunded" : 0,
  "currency" : "USD",
  "description" : "Test Charge",
  "card" : {
    "id" : "card_urfrNYFhi4YOT6DhO8FI88vQ",
    "created" : 1743725079,
    "objectType" : "card",
    "first6" : "424242",
    "last4" : "4242",
    "fingerprint" : "Qyq7hEnNyoQikknV",
    "expMonth" : "12",
    "expYear" : "2025",
    "brand" : "Visa",
    "type" : "Credit Card",
    "country" : "US",
    "issuer" : "SHIFT4 TEST",
    "merchantAccountId" : "ma_qmn0We4Rf4wX8UT0TrMGS0yX"
  },
  "captured" : true,
  "refunded" : false,
  "disputed" : false,
  "fraudDetails" : {
    "status" : "in_progress"
  },
  "avsCheck" : {
    "result" : "unavailable"
  },
  "status" : "successful",
  "clientObjectId" : "client_char_HNWDWl8DjQcyCCMNv0zZmjIA",
  "merchantAccountId" : "ma_qmn0We4Rf4wX8UT0TrMGS0yX"
}
STR;

    public function testExecute(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        /** @var Client $client */
        $moorwandClient = self::getContainer()->get('httplug.client.payment_gateway_shift4');
        $moorwandClient->addResponse(
            new PsrResponse(
                200,
                [
                    'Content-Type' => 'application/json',
                ],
                self::SUCCESS_RESPONSE
            )
        );

        $command = $application->find('app:payment:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'paymentGatewaySlug' => 'shift4',
            'amount' => '12.34',
            'currency' => 'USD',
            'card.number' => '4242424242424242',
            'card.expYear' => '2025',
            'card.expMonth' => '12',
            'card.cvv' => '123',
        ]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        self::assertStringContainsString(
            '{"transactionId":"char_Zq8xv5DJDgbjgLYpUFCb9WSj","createdAt":"2025-04-04T00:04:39+00:00","amount":"12.34","currency":"USD","cardBin":"424242"}',
            $output
        );
    }
}
