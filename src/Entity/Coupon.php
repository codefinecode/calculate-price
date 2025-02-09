<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\CouponType;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[ORM\Table(name: 'coupons')]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $code = null;

    #[ORM\Column(type: 'string', enumType: CouponType::class)]
    private ?CouponType $type = null;

    #[ORM\Column(type: 'float')]
    private ?float $value = null;


    public function __construct(string $code, CouponType $type, float $value)
    {
        $this->code = $code;
        $this->type = $type;
        $this->value = $value;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getType(): ?CouponType
    {
        return $this->type;
    }

    public function setType(CouponType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}