<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Money;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY|\Attribute::TARGET_METHOD|\Attribute::IS_REPEATABLE)]
class AmountValid extends Constraint
{
    public const string CODE_INVALID = '954018e0-0fe6-495b-8939-ffec4caa33b9';

    public function __construct(
        public string $currency,
        public string $message = 'Invalid money amount "{{ value }}".',
        ?array $options = null,
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options ?? [], $groups, $payload);
    }
}
