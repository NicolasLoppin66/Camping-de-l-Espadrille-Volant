<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'product_id', targetEntity: Disponibilites::class)]
    private Collection $product_dispo;

    #[ORM\OneToMany(mappedBy: 'product_id', targetEntity: Bookings::class)]
    private Collection $bookings;

    #[ORM\ManyToOne(targetEntity:Owners::class ,inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Owners $owner_id = null;

    #[ORM\OneToMany(targetEntity: RentalsPhotos::class, mappedBy: 'product_id')]
    private Collection $photos;

    #[ORM\ManyToOne(targetEntity:RentalsTypes::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RentalsTypes $rental_type = null;

    public function __construct()
    {
        $this->product_dispo = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Disponibilites>
     */
    public function getProductDispo(): Collection
    {
        return $this->product_dispo;
    }

    public function addProductDispo(Disponibilites $productDispo): self
    {
        if (!$this->product_dispo->contains($productDispo)) {
            $this->product_dispo->add($productDispo);
            $productDispo->setProductId($this);
        }

        return $this;
    }

    public function removeProductDispo(Disponibilites $productDispo): self
    {
        if ($this->product_dispo->removeElement($productDispo)) {
            // set the owning side to null (unless already changed)
            if ($productDispo->getProductId() === $this) {
                $productDispo->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bookings>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Bookings $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setProductId($this);
        }

        return $this;
    }

    public function removeBooking(Bookings $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getProductId() === $this) {
                $booking->setProductId(null);
            }
        }

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

    /**
     * @return Collection<int, RentalsPhotos>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(RentalsPhotos $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setProductId($this);
        }

        return $this;
    }

    public function removePhoto(RentalsPhotos $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProductId() === $this) {
                $photo->setProductId(null);
            }
        }

        return $this;
    }

    public function getRentalType(): ?RentalsTypes
    {
        return $this->rental_type;
    }

    public function setRentalType(?RentalsTypes $rental_type): self
    {
        $this->rental_type = $rental_type;

        return $this;
    }
}
