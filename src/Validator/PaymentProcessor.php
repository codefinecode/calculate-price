<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute] class PaymentProcessor extends Constraint
{
    public string $message = 'Invalid payment processor "{{ value }}". Allowed values are: {{ allowed_values }}.';
} 