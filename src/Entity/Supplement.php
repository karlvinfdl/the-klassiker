<?php

namespace App\Entity;

use App\Repository\SupplementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplementRepository::class)]
#[ORM\Table(name: 'supplement')]
class Supplement
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(type: 'string', length: 255)]
  private string $name;

  #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
  private float $price;

  #[ORM\Column(type: 'boolean')]
  private bool $isActive = true;

  #[ORM\Column(type: 'integer')]
  private int $displayOrder = 0;

  public function getId(): ?int { return $this->id; }

  public function getName(): string { return $this->name; }
  public function setName(string $name): self { $this->name = $name; return $this; }

  public function getPrice(): float { return $this->price; }
  public function setPrice(float $price): self { $this->price = $price; return $this; }

  public function isActive(): bool { return $this->isActive; }
  public function setIsActive(bool $isActive): self { $this->isActive = $isActive; return $this; }

  public function getDisplayOrder(): int { return $this->displayOrder; }
  public function setDisplayOrder(int $displayOrder): self { $this->displayOrder = $displayOrder; return $this; }

  public function __toString(): string { return $this->name; }
}
