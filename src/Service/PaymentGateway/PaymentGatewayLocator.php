<?php

declare(strict_types=1);

namespace App\Service\PaymentGateway;

use App\Exception\PaymentGateway\PaymentGatewayNotFoundException;
use Psr\Container\ContainerInterface;

readonly class PaymentGatewayLocator
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    /**
     * @throws PaymentGatewayNotFoundException
     */
    public function get(string $slug): PaymentGatewayInterface
    {
        if (! $this->container->has($slug)) {
            throw PaymentGatewayNotFoundException::create($slug);
        }

        return $this->container->get($slug);
    }
}
