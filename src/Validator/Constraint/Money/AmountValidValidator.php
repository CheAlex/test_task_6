<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Money;

use Money\Currency;
use Money\Exception\ParserException;
use Money\MoneyParser;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AmountValidValidator extends ConstraintValidator
{
    public function __construct(
        private readonly PropertyAccessorInterface $propertyAccessor,
        private readonly MoneyParser $moneyParser,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (! $constraint instanceof AmountValid) {
            throw new UnexpectedTypeException($constraint, AmountValid::class);
        }

        if (null === $value) {
            return;
        }

        $object = $this->context->getObject();
        assert(null !== $object);
        $currencyValue = $this->propertyAccessor->getValue($object, $constraint->currency);

        try {
            $this->moneyParser->parse($value, new Currency($currencyValue));
        } catch (ParserException $parserException) {
            $this->context->buildViolation($constraint->message)
                ->setCode(AmountValid::CODE_INVALID)
                ->setParameter('{{ value }}', (string) $value)
                ->addViolation()
            ;
        }
    }
}
