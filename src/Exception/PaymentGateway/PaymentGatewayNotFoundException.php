<?php

declare(strict_types=1);

namespace App\Exception\PaymentGateway;

class PaymentGatewayNotFoundException extends \Exception
{
    public static function create(string $slug): self
    {
        return new self(
            sprintf(
                'Payment gateway for slug="%s" not found.',
                $slug
            )
        );
    }
}
