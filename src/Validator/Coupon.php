<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute] class Coupon extends Constraint
{
    public string $message = 'The coupon "{{ value }}" is invalid.';
    public const NO_SUCH_COUPON_ERROR = 'no_such_coupon';
}