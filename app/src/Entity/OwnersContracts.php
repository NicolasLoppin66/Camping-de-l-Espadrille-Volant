<?php

namespace App\Entity;

use App\Repository\OwnersContractsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OwnersContractsRepository::class)]
class OwnersContracts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $contract_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContractDate(): ?\DateTimeInterface
    {
        return $this->contract_date;
    }

    public function setContractDate(\DateTimeInterface $contract_date): self
    {
        $this->contract_date = $contract_date;

        return $this;
    }
}
