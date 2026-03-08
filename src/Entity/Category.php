<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'category')]
#[ORM\HasLifecycleCallbacks]
class Category
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(type: 'string', length: 100)]
  private string $name;

  #[ORM\Column(type: 'string', length: 110, unique: true)]
  private string $slug;

  #[ORM\Column(type: 'text', nullable: true)]
  private ?string $description = null;

  #[ORM\Column(type: 'integer')]
  private int $displayOrder = 0;

  #[ORM\Column(type: 'boolean')]
  private bool $isActive = true;

  #[ORM\OneToMany(mappedBy: 'category', targetEntity: Dish::class, orphanRemoval: true)]
  #[ORM\OrderBy(['displayOrder' => 'ASC'])]
  private Collection $dishes;

  #[ORM\Column(type: 'datetime')]
  private \DateTimeInterface $createdAt;

  #[ORM\Column(type: 'datetime', nullable: true)]
  private ?\DateTimeInterface $updatedAt = null;

  public function __construct()
  {
    $this->dishes = new ArrayCollection();
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

  public function getSlug(): string
  {
    return $this->slug;
  }

  public function setSlug(string $slug): self
  {
    $this->slug = $slug;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): self
  {
    $this->description = $description;

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

  /**
   * @return Collection<int, Dish>
   */
  public function getDishes(): Collection
  {
    return $this->dishes;
  }

  public function addDish(Dish $dish): self
  {
    if (!$this->dishes->contains($dish)) {
      $this->dishes->add($dish);
      $dish->setCategory($this);
    }

    return $this;
  }

  public function removeDish(Dish $dish): self
  {
    if ($this->dishes->removeElement($dish)) {
      // set the owning side to null (unless already changed)
      if ($dish->getCategory() === $this) {
        $dish->setCategory(null);
      }
    }

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

