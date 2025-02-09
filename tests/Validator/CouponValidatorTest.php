<?php

namespace App\Tests\Validator;

use App\Entity\Coupon;
use App\Enum\CouponType;
use App\Repository\CouponRepository;
use App\Validator\Coupon as CouponConstraint;
use App\Validator\CouponValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CouponValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): CouponValidator
    {
        // Создаем мок репозитория
        $repository = $this->createMock(CouponRepository::class);
        $repository->method('findOneByCode')->willReturnCallback(function (string $code): ?Coupon {
            return match ($code) {
                'D15' => new Coupon(code: 'D15', type: CouponType::FIXED, value: 15),
                default => null,
            };
        });

        // Возвращаем валидатор с внедренным репозиторием
        return new CouponValidator($repository);
    }

    public function testValidCoupon(): void
    {
        $this->validator->validate('D15', new CouponConstraint());
        $this->assertNoViolation();
    }

    public function testInvalidCoupon(): void
    {
        $this->validator->validate('INVALID123', new CouponConstraint());

        $this->buildViolation('The coupon "{{ value }}" is invalid.')
            ->setParameter('{{ value }}', 'INVALID123')
            ->setCode(CouponConstraint::NO_SUCH_COUPON_ERROR)
            ->assertRaised();
    }

    public function testEmptyCoupon(): void
    {
        $this->validator->validate('', new CouponConstraint());
        $this->assertNoViolation();
    }
}