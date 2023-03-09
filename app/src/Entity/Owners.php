<?php

namespace App\Entity;

use App\Repository\OwnersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: OwnersRepository::class)]
class Owners implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /**
     * Summary of id
     * @var int|null
     */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * Summary of firstName
     * @var string|null
     */
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    /**
     * Summary of lastName
     * @var string|null
     */
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    /**
     * Summary of email
     * @var string|null
     */
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    /**
     * Summary of telephone
     * @var string|null
     */
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    /**
     * Summary of password
     * @var string|null
     */
    private ?string $password = null;

    #[ORM\Column]
    /**
     * Summary of dateRetentionConsent
     * @var bool|null
     */
    private ?bool $dateRetentionConsent = null;

    #[ORM\Column(length: 255)]
    /**
     * Summary of role
     * @var string|null
     */
    private ?string $role = null;

    #[ORM\ManyToOne(targetEntity: Addresses::class, inversedBy: 'owners')]
    #[ORM\JoinColumn]
    /**
     * Summary of address_id
     * @var Addresses|null
     */
    private ?Addresses $address_id = null;

    #[ORM\OneToMany(targetEntity: Products::class, mappedBy: 'owner_id')]
    /**
     * Summary of products
     * @var Collection
     */
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'owner_id', targetEntity: OwnersContracts::class)]
    /**
     * Summary of ownersContracts
     * @var Collection
     */
    private Collection $ownersContracts;

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->ownersContracts = new ArrayCollection();
    }

    /**
     * Summary of getId
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Summary of getFirstName
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Summary of setFirstName
     * @param string $firstName
     * @return Owners
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Summary of getLastName
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Summary of setLastName
     * @param string $lastName
     * @return Owners
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Summary of getEmail
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Summary of setEmail
     * @param string $email
     * @return Owners
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Summary of getTelephone
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * Summary of setTelephone
     * @param string $telephone
     * @return Owners
     */
    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Summary of getPassword
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Summary of setPassword
     * @param string $password
     * @return Owners
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Summary of isDateRetentionConsent
     * @return bool|null
     */
    public function isDateRetentionConsent(): ?bool
    {
        return $this->dateRetentionConsent;
    }

    /**
     * Summary of setDateRetentionConsent
     * @param bool $dateRetentionConsent
     * @return Owners
     */
    public function setDateRetentionConsent(bool $dateRetentionConsent): self
    {
        $this->dateRetentionConsent = $dateRetentionConsent;

        return $this;
    }

    /**
     * Summary of getRole
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * Summary of setRole
     * @param string $role
     * @return Owners
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Summary of getAddressId
     * @return Addresses|null
     */
    public function getAddressId(): ?Addresses
    {
        return $this->address_id;
    }

    /**
     * Summary of setAddressId
     * @param Addresses|null $address_id
     * @return Owners
     */
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

    /**
     * Summary of addProduct
     * @param Products $product
     * @return Owners
     */
    public function addProduct(Products $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setOwnerId($this);
        }

        return $this;
    }

    /**
     * Summary of removeProduct
     * @param Products $product
     * @return Owners
     */
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
     * Summary of serialize
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->getId(),
            $this->getEmail(),
            $this->getPassword(),
        ]);
    }

    /**
     * Summary of unserialize
     * @param mixed $data
     * @return void
     */
    public function unserialize($data)
    {
        list($this->id, $this->email, $this->password)
            = unserialize($data, ['allowed_classes' => false]);
    }

    /**
     * @return Collection<int, OwnersContracts>
     */
    public function getOwnersContracts(): Collection
    {
        return $this->ownersContracts;
    }

    /**
     * Summary of addOwnersContract
     * @param OwnersContracts $ownersContract
     * @return Owners
     */
    public function addOwnersContract(OwnersContracts $ownersContract): self
    {
        if (!$this->ownersContracts->contains($ownersContract)) {
            $this->ownersContracts->add($ownersContract);
            $ownersContract->setOwnerId($this);
        }

        return $this;
    }

    /**
     * Summary of removeOwnersContract
     * @param OwnersContracts $ownersContract
     * @return Owners
     */
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
    /**
     * Returns the roles granted to the user.
     *
     * public function getRoles()
     * {
     * return ['ROLE_USER'];
     * }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     * @return array<string>
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     * @return mixed
     */
    public function eraseCredentials()
    {
    }

    /**
     * Returns the identifier for this user (e.g. username or email address).
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}