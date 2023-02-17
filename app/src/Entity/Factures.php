<?php

namespace App\Entity;

use App\Repository\FacturesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacturesRepository::class)]
class Factures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?int $address_id = null;

    #[ORM\Column(length: 255)]
    private ?string $rental_name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $piscine_pu_adult = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $piscine_pu_child = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $ts_pu_adult = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $ts_pu_child = null;

    #[ORM\Column]
    private ?int $nb_adult = null;

    #[ORM\Column]
    private ?int $nb_child = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $check_in = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $check_out = null;

    #[ORM\Column]
    private ?int $piscine_nb_adult = null;

    #[ORM\Column]
    private ?int $piscine_nb_child = null;

    #[ORM\Column]
    private ?int $product_id = null;

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

    public function getAddressId(): ?int
    {
        return $this->address_id;
    }

    public function setAddressId(int $address_id): self
    {
        $this->address_id = $address_id;

        return $this;
    }

    public function getRentalName(): ?string
    {
        return $this->rental_name;
    }

    public function setRentalName(string $rental_name): self
    {
        $this->rental_name = $rental_name;

        return $this;
    }

    public function getPiscinePuAdult(): ?string
    {
        return $this->piscine_pu_adult;
    }

    public function setPiscinePuAdult(string $piscine_pu_adult): self
    {
        $this->piscine_pu_adult = $piscine_pu_adult;

        return $this;
    }

    public function getPiscinePuChild(): ?string
    {
        return $this->piscine_pu_child;
    }

    public function setPiscinePuChild(string $piscine_pu_child): self
    {
        $this->piscine_pu_child = $piscine_pu_child;

        return $this;
    }

    public function getTsPuAdult(): ?string
    {
        return $this->ts_pu_adult;
    }

    public function setTsPuAdult(string $ts_pu_adult): self
    {
        $this->ts_pu_adult = $ts_pu_adult;

        return $this;
    }

    public function getTsPuChild(): ?string
    {
        return $this->ts_pu_child;
    }

    public function setTsPuChild(string $ts_pu_child): self
    {
        $this->ts_pu_child = $ts_pu_child;

        return $this;
    }

    public function getNbAdult(): ?int
    {
        return $this->nb_adult;
    }

    public function setNbAdult(int $nb_adult): self
    {
        $this->nb_adult = $nb_adult;

        return $this;
    }

    public function getNbChild(): ?int
    {
        return $this->nb_child;
    }

    public function setNbChild(int $nb_child): self
    {
        $this->nb_child = $nb_child;

        return $this;
    }

    public function getCheckIn(): ?\DateTimeInterface
    {
        return $this->check_in;
    }

    public function setCheckIn(\DateTimeInterface $check_in): self
    {
        $this->check_in = $check_in;

        return $this;
    }

    public function getCheckOut(): ?\DateTimeInterface
    {
        return $this->check_out;
    }

    public function setCheckOut(\DateTimeInterface $check_out): self
    {
        $this->check_out = $check_out;

        return $this;
    }

    public function getPiscineNbAdult(): ?int
    {
        return $this->piscine_nb_adult;
    }

    public function setPiscineNbAdult(int $piscine_nb_adult): self
    {
        $this->piscine_nb_adult = $piscine_nb_adult;

        return $this;
    }

    public function getPiscineNbChild(): ?int
    {
        return $this->piscine_nb_child;
    }

    public function setPiscineNbChild(int $piscine_nb_child): self
    {
        $this->piscine_nb_child = $piscine_nb_child;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }
}
