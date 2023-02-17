<?php

namespace App\Entity;

use App\Repository\AddressesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressesRepository::class)]
class Addresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num = null;

    #[ORM\Column(length: 50)]
    private ?string $road_type = null;

    #[ORM\Column(length: 255)]
    private ?string $road_name = null;

    #[ORM\Column]
    private ?int $zip = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getRoadType(): ?string
    {
        return $this->road_type;
    }

    public function setRoadType(string $road_type): self
    {
        $this->road_type = $road_type;

        return $this;
    }

    public function getRoadName(): ?string
    {
        return $this->road_name;
    }

    public function setRoadName(string $road_name): self
    {
        $this->road_name = $road_name;

        return $this;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
