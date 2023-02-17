<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
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
    private ?int $nb_adult = null;

    #[ORM\Column]
    private ?int $nb_child = null;

    #[ORM\Column]
    private ?int $access_piscine_adult = null;

    #[ORM\Column]
    private ?int $access_piscine_child = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $discount = null;

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

    public function getAccessPiscineAdult(): ?int
    {
        return $this->access_piscine_adult;
    }

    public function setAccessPiscineAdult(int $access_piscine_adult): self
    {
        $this->access_piscine_adult = $access_piscine_adult;

        return $this;
    }

    public function getAccessPiscineChild(): ?int
    {
        return $this->access_piscine_child;
    }

    public function setAccessPiscineChild(int $access_piscine_child): self
    {
        $this->access_piscine_child = $access_piscine_child;

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
}
