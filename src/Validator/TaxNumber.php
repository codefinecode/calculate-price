<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute] class TaxNumber extends Constraint
{
    public string $message = 'The tax number "{{ value }}" is not valid.';
}