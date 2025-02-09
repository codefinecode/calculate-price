<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PaymentProcessorValidator extends ConstraintValidator
{
    private const ALLOWED_PROCESSORS = ['paypal', 'stripe'];

    public function validate($value, Constraint $constraint): void
    {
        if (null === $value) {
            return;
        }

        if (!in_array($value, self::ALLOWED_PROCESSORS)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ allowed_values }}', implode(', ', self::ALLOWED_PROCESSORS))
                ->addViolation();
        }
    }
} 