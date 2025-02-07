<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute] class Coupon extends Constraint
{
    public string $message = 'The coupon "{{ value }}" does not exist or is invalid.';
}