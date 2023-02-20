<?php

namespace App\Entity;

use App\Repository\BillsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BillsRepository::class)]
class Bills
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
    private ?string $pool_adult_pu = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $pool_kid_pu = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $ts_adult_pu = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $ts_kid_pu = null;

    #[ORM\Column]
    private ?int $nb_adult = null;

    #[ORM\Column]
    private ?int $nb_kid = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $check_in = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $check_out = null;

    #[ORM\Column]
    private ?int $pool_adult_nb = null;

    #[ORM\Column]
    private ?int $pool_kid_nb = null;

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

    public function getPoolAdultPu(): ?string
    {
        return $this->pool_adult_pu;
    }

    public function setPoolAdultPu(string $pool_adult_pu): self
    {
        $this->pool_adult_pu = $pool_adult_pu;

        return $this;
    }

    public function getPoolKidPu(): ?string
    {
        return $this->pool_kid_pu;
    }

    public function setPoolKidPu(string $pool_kid_pu): self
    {
        $this->pool_kid_pu = $pool_kid_pu;

        return $this;
    }

    public function getTsAdultPu(): ?string
    {
        return $this->ts_adult_pu;
    }

    public function setTsAdultPu(string $ts_adult_pu): self
    {
        $this->ts_adult_pu = $ts_adult_pu;

        return $this;
    }

    public function getTsKidPu(): ?string
    {
        return $this->ts_kid_pu;
    }

    public function setTsKidPu(string $ts_kid_pu): self
    {
        $this->ts_kid_pu = $ts_kid_pu;

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

    public function getNbKid(): ?int
    {
        return $this->nb_kid;
    }

    public function setNbKid(int $nb_kid): self
    {
        $this->nb_kid = $nb_kid;

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

    public function getPoolAdultNb(): ?int
    {
        return $this->pool_adult_nb;
    }

    public function setPoolAdultNb(int $pool_adult_nb): self
    {
        $this->pool_adult_nb = $pool_adult_nb;

        return $this;
    }

    public function getPoolKidNb(): ?int
    {
        return $this->pool_kid_nb;
    }

    public function setPoolKidNb(int $pool_kid_nb): self
    {
        $this->pool_kid_nb = $pool_kid_nb;

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
