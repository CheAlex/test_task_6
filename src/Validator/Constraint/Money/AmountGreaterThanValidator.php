<?php

declare(strict_types=1);

namespace App\Validator\Constraint\Money;

use Money\Currency;
use Money\MoneyParser;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraints\AbstractComparisonValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AmountGreaterThanValidator extends AbstractComparisonValidator
{
    public function __construct(
        protected readonly MoneyParser $moneyParser,
        protected PropertyAccessorInterface $propertyAccessor
    ) {
        parent::__construct($propertyAccessor);
    }

    protected function compareValues(mixed $value1, mixed $value2): bool
    {
        assert($this->context instanceof ExecutionContext);

        $constraint = $this->context->getConstraint();

        if (! $constraint instanceof AmountGreaterThan) {
            throw new UnexpectedTypeException($constraint, AmountGreaterThan::class);
        }

        $object = $this->context->getObject();
        assert(null !== $object);
        $currencyValue = $this->propertyAccessor->getValue($object, $constraint->currency);

        $valueVo1 = $this->moneyParser->parse((string) $value1, new Currency($currencyValue));
        $valueVo2 = $this->moneyParser->parse((string) $value2, new Currency($currencyValue));

        return $valueVo1->greaterThan($valueVo2);
    }

    protected function getErrorCode(): ?string
    {
        return AmountGreaterThan::TOO_LOW_ERROR;
    }
}
