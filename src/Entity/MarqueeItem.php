<?php

namespace App\Entity;

use App\Repository\MarqueeItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarqueeItemRepository::class)]
#[ORM\Table(name: 'marquee_item')]
class MarqueeItem
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(type: 'string', length: 255)]
  private string $text;

  #[ORM\Column(type: 'boolean')]
  private bool $isActive = true;

  #[ORM\Column(type: 'integer')]
  private int $displayOrder = 0;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getText(): string
  {
    return $this->text;
  }

  public function setText(string $text): self
  {
    $this->text = $text;
    return $this;
  }

  public function isActive(): bool
  {
    return $this->isActive;
  }

  public function setIsActive(bool $isActive): self
  {
    $this->isActive = $isActive;
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

  public function __toString(): string
  {
    return $this->text;
  }
}
