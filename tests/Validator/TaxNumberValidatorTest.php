<?php

namespace App\Tests\Validator;

use App\Validator\TaxNumber;
use App\Validator\TaxNumberValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use PHPUnit\Framework\TestCase;

class TaxNumberValidatorTest extends TestCase
{
    public function testValidTaxNumbers(): void
    {
        $validator = Validation::createValidator();
        $constraint = new TaxNumber();

        $violations = $validator->validate('DE123456789', $constraint);
        $this->assertCount(0, $violations);

        $violations = $validator->validate('IT12345678900', $constraint);
        $this->assertCount(0, $violations);

        $violations = $validator->validate('GR123456789', $constraint);
        $this->assertCount(0, $violations);

        $violations = $validator->validate('FRYY123456789', $constraint);
        $this->assertCount(0, $violations);
    }

    public function testInvalidTaxNumbers(): void
    {
        $validator = Validation::createValidator();
        $constraint = new TaxNumber();

        $violations = $validator->validate('INVALID123', $constraint);
        $this->assertCount(1, $violations);

        $violations = $validator->validate('DE1234567891', $constraint);
        $this->assertCount(1, $violations);

        $violations = $validator->validate('IT12345678900-', $constraint);
        $this->assertCount(1, $violations);

        $violations = $validator->validate('GR123456789.', $constraint);
        $this->assertCount(1, $violations);

        $violations = $validator->validate('&nbsp;FRYY123456789', $constraint);
        $this->assertCount(1, $violations);
    }
}