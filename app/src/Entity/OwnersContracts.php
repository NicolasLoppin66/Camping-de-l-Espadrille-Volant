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

    #[ORM\ManyToOne(inversedBy: 'ownerContracts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Owners $product_id = null;

    #[ORM\ManyToOne(inversedBy: 'ownersContracts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Owners $owner_id = null;

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

    public function getProductId(): ?Owners
    {
        return $this->product_id;
    }

    public function setProductId(?Owners $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getOwnerId(): ?Owners
    {
        return $this->owner_id;
    }

    public function setOwnerId(?Owners $owner_id): self
    {
        $this->owner_id = $owner_id;

        return $this;
    }
}
