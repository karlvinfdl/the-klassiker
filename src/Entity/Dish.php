<?php

namespace App\Entity;

use App\Repository\DishRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DishRepository::class)]
#[ORM\Table(name: 'dish')]
#[ORM\HasLifecycleCallbacks]
class Dish
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(type: 'string', length: 150)]
  private string $name;

  #[ORM\Column(type: 'text')]
  private string $description;

  #[ORM\Column(type: 'decimal', precision: 6, scale: 2)]
  private string $price;

  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  private ?string $image = null;

  #[ORM\Column(type: 'integer')]
  private int $displayOrder = 0;

  #[ORM\Column(type: 'boolean')]
  private bool $isActive = true;

  #[ORM\Column(type: 'boolean')]
  private bool $isFeatured = false;

  #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'dishes')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Category $category = null;

  #[ORM\Column(type: 'datetime')]
  private \DateTimeInterface $createdAt;

  #[ORM\Column(type: 'datetime', nullable: true)]
  private ?\DateTimeInterface $updatedAt = null;

  public function __construct()
  {
    $this->createdAt = new \DateTimeImmutable();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getPrice(): string
  {
    return $this->price;
  }

  public function setPrice(string $price): self
  {
    $this->price = $price;

    return $this;
  }

  public function getImage(): ?string
  {
    return $this->image;
  }

  public function setImage(?string $image): self
  {
    $this->image = $image;

    return $this;
  }

  public function getDisplayOrder(): int
  {
    return $this->displayOrder;
  }

  public function setDisplayOrder(int $displayOrder): self
  {
    $this->displayOrder = $displayOrder;

    return $this;
  }

  public function isIsActive(): bool
  {
    return $this->isActive;
  }

  public function setIsActive(bool $isActive): self
  {
    $this->isActive = $isActive;

    return $this;
  }

  public function isIsFeatured(): bool
  {
    return $this->isFeatured;
  }

  public function setIsFeatured(bool $isFeatured): self
  {
    $this->isFeatured = $isFeatured;

    return $this;
  }

  public function getCategory(): ?Category
  {
    return $this->category;
  }

  public function setCategory(?Category $category): self
  {
    $this->category = $category;

    return $this;
  }

  public function getCreatedAt(): \DateTimeInterface
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTimeInterface $createdAt): self
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getUpdatedAt(): ?\DateTimeInterface
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  #[ORM\PreUpdate]
  public function preUpdate(): void
  {
    $this->updatedAt = new \DateTimeImmutable();
  }

  public function __toString(): string
  {
    return $this->name;
  }
}

