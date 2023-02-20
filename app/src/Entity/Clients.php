<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $firstName = null;

    #[ORM\Column(length: 150)]
    private ?string $lastName = null;

    #[ORM\Column(length: 200)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $eraseDataDay = null;

    #[ORM\Column]
    private ?bool $dataRetentionConsent = null;

    #[ORM\ManyToOne(targetEntity:Addresses::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Addresses $address_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEraseDataDay(): ?\DateTimeInterface
    {
        return $this->eraseDataDay;
    }

    public function setEraseDataDay(\DateTimeInterface $eraseDataDay): self
    {
        $this->eraseDataDay = $eraseDataDay;

        return $this;
    }

    public function isDataRetentionConsent(): ?bool
    {
        return $this->dataRetentionConsent;
    }

    public function setDataRetentionConsent(bool $dataRetentionConsent): self
    {
        $this->dataRetentionConsent = $dataRetentionConsent;

        return $this;
    }

    public function getAddressId(): ?Addresses
    {
        return $this->address_id;
    }

    public function setAddressId(?Addresses $address_id): self
    {
        $this->address_id = $address_id;

        return $this;
    }
}
