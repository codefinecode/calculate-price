<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\CouponRepository;

class CouponValidator extends ConstraintValidator
{
    public function __construct(private CouponRepository $couponRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!is_string($value) || '' === $value) {
            return;
        }

        if (!$this->couponRepository->findOneByCode($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->setCode(Coupon::NO_SUCH_COUPON_ERROR)
                ->addViolation();
        }
    }
}