<?php

namespace App\Entity;

use App\Repository\ExtraChargesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExtraChargesRepository::class)]
class ExtraCharges
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $label = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $amount_adults = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $amount_kids = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getAmountAdults(): ?string
    {
        return $this->amount_adults;
    }

    public function setAmountAdults(string $amount_adults): self
    {
        $this->amount_adults = $amount_adults;

        return $this;
    }

    public function getAmountKids(): ?string
    {
        return $this->amount_kids;
    }

    public function setAmountKids(string $amount_kids): self
    {
        $this->amount_kids = $amount_kids;

        return $this;
    }
}
