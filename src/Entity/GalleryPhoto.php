<?php

namespace App\Entity;

use App\Repository\GalleryPhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GalleryPhotoRepository::class)]
#[ORM\Table(name: 'gallery_photo')]
#[ORM\HasLifecycleCallbacks]
class GalleryPhoto
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(type: 'string', length: 255)]
  private string $filename;

  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  private ?string $altText = null;

  #[ORM\Column(type: 'integer')]
  private int $displayOrder = 0;

  #[ORM\Column(type: 'boolean')]
  private bool $isActive = true;

  #[ORM\Column(type: 'datetime')]
  private \DateTimeInterface $createdAt;

  public function __construct()
  {
    $this->createdAt = new \DateTimeImmutable();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getFilename(): string
  {
    return $this->filename;
  }

  public function setFilename(string $filename): self
  {
    $this->filename = $filename;

    return $this;
  }

  public function getAltText(): ?string
  {
    return $this->altText;
  }

  public function setAltText(?string $altText): self
  {
    $this->altText = $altText;

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

  public function getCreatedAt(): \DateTimeInterface
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTimeInterface $createdAt): self
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function __toString(): string
  {
    return $this->filename;
  }
}

