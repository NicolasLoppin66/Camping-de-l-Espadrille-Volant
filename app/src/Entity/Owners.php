<?php

namespace App\Entity;

use App\Repository\OwnersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OwnersRepository::class)]
class Owners
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $dateRetentionConsent = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\ManyToOne(targetEntity:Addresses::class, inversedBy:'owners')]
    #[ORM\JoinColumn]
    private ?Addresses $address_id = null;

    #[ORM\OneToMany(targetEntity: Products::class, mappedBy: 'owner_id')]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'owner_id', targetEntity: OwnersContracts::class)]
    private Collection $ownersContracts;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->ownersContracts = new ArrayCollection();
    }

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isDateRetentionConsent(): ?bool
    {
        return $this->dateRetentionConsent;
    }

    public function setDateRetentionConsent(bool $dateRetentionConsent): self
    {
        $this->dateRetentionConsent = $dateRetentionConsent;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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

    /**
     * @return Collection<int, Products>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setOwnerId($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOwnerId() === $this) {
                $product->setOwnerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OwnersContracts>
     */
    public function getOwnersContracts(): Collection
    {
        return $this->ownersContracts;
    }

    public function addOwnersContract(OwnersContracts $ownersContract): self
    {
        if (!$this->ownersContracts->contains($ownersContract)) {
            $this->ownersContracts->add($ownersContract);
            $ownersContract->setOwnerId($this);
        }

        return $this;
    }

    public function removeOwnersContract(OwnersContracts $ownersContract): self
    {
        if ($this->ownersContracts->removeElement($ownersContract)) {
            // set the owning side to null (unless already changed)
            if ($ownersContract->getOwnerId() === $this) {
                $ownersContract->setOwnerId(null);
            }
        }

        return $this;
    }
}
