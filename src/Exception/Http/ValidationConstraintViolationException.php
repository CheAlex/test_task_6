<?php

declare(strict_types=1);

namespace App\Exception\Http;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationConstraintViolationException extends HttpException
{
    public static function create(ConstraintViolation $constraintViolation): self
    {
        $violations = new ConstraintViolationList();
        $violations->add($constraintViolation);

        return new self(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            implode("\n", array_map(static fn ($e) => $e->getMessage(), iterator_to_array($violations))),
            new ValidationFailedException(null, $violations)
        );
    }
}
