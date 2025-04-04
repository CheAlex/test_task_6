<?php

declare(strict_types=1);

namespace App\Command\Payment;

use App\Exception\PaymentGateway\PaymentGatewayNotFoundException;
use App\Request\V1\Payment\CreatePaymentRequest;
use App\Response\V1\Payment\CreatePaymentResponse;
use App\Service\PaymentService;
use App\Service\PaymentService\CreatePaymentData;
use App\Util\ArrayUtil;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreatePaymentCommand extends Command
{
    public function __construct(
        private readonly NormalizerInterface $normalizer,
        private readonly ValidatorInterface $validator,
        private readonly PaymentService $paymentService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:payment:create')
            ->setDescription('Create card payment')
            ->addArgument('paymentGatewaySlug', InputArgument::REQUIRED, 'Payment gateway slug')
            ->addArgument('amount', InputArgument::REQUIRED, 'Decimal amount')
            ->addArgument('currency', InputArgument::REQUIRED, 'Currency')
            ->addArgument('card.number', InputArgument::REQUIRED, 'Card number')
            ->addArgument('card.expYear', InputArgument::REQUIRED, 'Card expiration year')
            ->addArgument('card.expMonth', InputArgument::REQUIRED, 'Card expiration month')
            ->addArgument('card.cvv', InputArgument::REQUIRED, 'Card CVV')
            ->addUsage('shift4 12.34 USD 4242424242424242 2025 12 123')
            ->addUsage('aci 12.34 EUR 4200000000000000 2034 05 123')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $arguments = $input->getArguments();

        $paymentGatewaySlug = $arguments['paymentGatewaySlug'];
        unset($arguments['paymentGatewaySlug']);
        unset($arguments['command']);

        $createPaymentRequestData = ArrayUtil::unflattenArray($arguments);
        $createPaymentRequest = $this->normalizer->denormalize($createPaymentRequestData, CreatePaymentRequest::class);

        $constraintViolationList = $this->validator->validate($createPaymentRequest);
        if (0 !== $constraintViolationList->count()) {
            throw new ValidationFailedException($createPaymentRequest, $constraintViolationList);
        }

        try {
            $createPaymentResult = $this->paymentService->createPayment(
                $paymentGatewaySlug,
                CreatePaymentData::createRequest($createPaymentRequest)
            );
        } catch (PaymentGatewayNotFoundException $exception) {
            $violations = new ConstraintViolationList();
            $violations->add(new ConstraintViolation($exception->getMessage(), null, [], null, null, null));

            throw new ValidationFailedException($createPaymentRequest, $constraintViolationList);
        }

        $createPaymentResponse = CreatePaymentResponse::create($createPaymentResult);

        $output->writeln(json_encode($createPaymentResponse, JSON_THROW_ON_ERROR));

        return Command::SUCCESS;
    }
}
