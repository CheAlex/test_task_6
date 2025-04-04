<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Money;

use Money\Currencies;
use Money\Currency;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CurrencyExistsValidator extends ConstraintValidator
{
    /**
     * @param Currencies<Currency> $currencyList
     */
    public function __construct(
        private readonly Currencies $currencyList
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (! $constraint instanceof CurrencyExists) {
            throw new UnexpectedTypeException($constraint, CurrencyExists::class);
        }

        if (null === $value) {
            return;
        }

        if (is_string($value) && '' !== $value) {
            $currency = new Currency($value);
        } elseif ($value instanceof Currency) {
            $currency = $value;
        } else {
            throw new UnexpectedValueException($value, '[' . Currency::class . '| string]');
        }

        if (! $this->currencyList->contains($currency)) {
            $this->context->buildViolation($constraint->message)
                ->setCode(CurrencyExists::CODE_INVALID)
                ->setParameter('{{ value }}', (string) $value)
                ->addViolation()
            ;
        }
    }
}
