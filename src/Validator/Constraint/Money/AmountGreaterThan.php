<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Money;

use Symfony\Component\Validator\Constraints\AbstractComparison;

#[\Attribute(\Attribute::TARGET_PROPERTY|\Attribute::TARGET_METHOD|\Attribute::IS_REPEATABLE)]
class AmountGreaterThan extends AbstractComparison
{
    public const string TOO_LOW_ERROR = '0c915981-7079-4615-a034-bd7b35b575c5';

    protected const array ERROR_NAMES = [
        self::TOO_LOW_ERROR => 'TOO_LOW_ERROR',
    ];

    public function __construct(
        public string $currency,
        mixed $value,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = []
    ) {
        parent::__construct(
            $value,
            $propertyPath,
                $message ?? 'This value should be greater than {{ compared_value }}.',
            $groups,
            $payload,
            $options
        );
    }
}
