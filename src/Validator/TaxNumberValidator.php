<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TaxNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $patterns = [
            'DE' => '/^DE\d{9}$/',
            'IT' => '/^IT\d{11}$/',
            'GR' => '/^GR\d{9}$/',
            'FR' => '/^FR[A-Z]{2}\d{9}$/',
        ];

        $countryCode = substr($value, 0, 2);
        $pattern = $patterns[$countryCode] ?? null;

        if (!$pattern || !preg_match($pattern, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}