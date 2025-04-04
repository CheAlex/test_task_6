<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Money;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY|\Attribute::TARGET_METHOD|\Attribute::IS_REPEATABLE)]
class CurrencyExists extends Constraint
{
    public const string CODE_INVALID = '0bb39452-ac33-4b8a-ae7f-01fa25fd82d5';

    public string $message = 'Undefined currency "{{ value }}".';
}
