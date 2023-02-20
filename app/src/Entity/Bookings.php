<?php

namespace App\Entity;

use App\Repository\BookingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingsRepository::class)]
class Bookings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $check_in = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $check_out = null;

    #[ORM\Column]
    private ?int $nb_adults = null;

    #[ORM\Column]
    private ?int $nb_kids = null;

    #[ORM\Column]
    private ?int $pool_access_adults = null;

    #[ORM\Column]
    private ?int $pool_access_kids = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $discount = null;

    #[ORM\ManyToOne(targetEntity:Products::class, inversedBy: 'bookings')]
    private ?Products $product_id = null;

    #[ORM\ManyToOne(targetEntity:Clients::class)]
    private ?Clients $client_id = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNbAdults(): ?int
    {
        return $this->nb_adults;
    }

    public function setNbAdults(int $nb_adults): self
    {
        $this->nb_adults = $nb_adults;

        return $this;
    }

    public function getNbKids(): ?int
    {
        return $this->nb_kids;
    }

    public function setNbKids(int $nb_kids): self
    {
        $this->nb_kids = $nb_kids;

        return $this;
    }

    public function getPoolAccessAdults(): ?int
    {
        return $this->pool_access_adults;
    }

    public function setPoolAccessAdults(int $pool_access_adults): self
    {
        $this->pool_access_adults = $pool_access_adults;

        return $this;
    }

    public function getPoolAccessKids(): ?int
    {
        return $this->pool_access_kids;
    }

    public function setPoolAccessKids(int $pool_access_kids): self
    {
        $this->pool_access_kids = $pool_access_kids;

        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(string $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getProductId(): ?Products
    {
        return $this->product_id;
    }

    public function setProductId(?Products $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getClientId(): ?Clients
    {
        return $this->client_id;
    }

    public function setClientId(?Clients $client_id): self
    {
        $this->client_id = $client_id;

        return $this;
    }
}
