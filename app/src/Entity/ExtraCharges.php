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
    private ?string $amount_adult = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $amount_child = null;

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

    public function getAmountAdult(): ?string
    {
        return $this->amount_adult;
    }

    public function setAmountAdult(string $amount_adult): self
    {
        $this->amount_adult = $amount_adult;

        return $this;
    }

    public function getAmountChild(): ?string
    {
        return $this->amount_child;
    }

    public function setAmountChild(string $amount_child): self
    {
        $this->amount_child = $amount_child;

        return $this;
    }
}
