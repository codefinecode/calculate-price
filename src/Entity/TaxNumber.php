<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class TaxNumber
{
    #[ORM\Column(type: 'string', length: 2)]
    private string $countryCode;

    #[ORM\Column(type: 'string', length: 20)]
    private string $number;

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getValue(): string
    {
        return $this->number;
    }

    public function setValue(string $number): void
    {
        $this->number = $number;
    }
}