<?php

namespace App\Entity;

use App\Repository\OpeningHoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpeningHoursRepository::class)]
#[ORM\Table(name: 'opening_hours')]
class OpeningHours
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(type: 'string', length: 20)]
  private string $dayName;

  #[ORM\Column(type: 'integer')]
  private int $dayOfWeek; // 0 = dimanche, 1 = lundi, ..., 6 = samedi

  #[ORM\Column(type: 'time', nullable: true)]
  private ?\DateTimeInterface $morningOpen = null;

  #[ORM\Column(type: 'time', nullable: true)]
  private ?\DateTimeInterface $morningClose = null;

  #[ORM\Column(type: 'time', nullable: true)]
  private ?\DateTimeInterface $afternoonOpen = null;

  #[ORM\Column(type: 'time', nullable: true)]
  private ?\DateTimeInterface $afternoonClose = null;

  #[ORM\Column(type: 'boolean')]
  private bool $isClosed = false;

  #[ORM\Column(type: 'integer')]
  private int $displayOrder = 0;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getDayName(): string
  {
    return $this->dayName;
  }

  public function setDayName(string $dayName): self
  {
    $this->dayName = $dayName;

    return $this;
  }

  public function getDayOfWeek(): int
  {
    return $this->dayOfWeek;
  }

  public function setDayOfWeek(int $dayOfWeek): self
  {
    $this->dayOfWeek = $dayOfWeek;

    return $this;
  }

  public function getMorningOpen(): ?\DateTimeInterface
  {
    return $this->morningOpen;
  }

  public function setMorningOpen(?\DateTimeInterface $morningOpen): self
  {
    $this->morningOpen = $morningOpen;

    return $this;
  }

  public function getMorningClose(): ?\DateTimeInterface
  {
    return $this->morningClose;
  }

  public function setMorningClose(?\DateTimeInterface $morningClose): self
  {
    $this->morningClose = $morningClose;

    return $this;
  }

  public function getAfternoonOpen(): ?\DateTimeInterface
  {
    return $this->afternoonOpen;
  }

  public function setAfternoonOpen(?\DateTimeInterface $afternoonOpen): self
  {
    $this->afternoonOpen = $afternoonOpen;

    return $this;
  }

  public function getAfternoonClose(): ?\DateTimeInterface
  {
    return $this->afternoonClose;
  }

  public function setAfternoonClose(?\DateTimeInterface $afternoonClose): self
  {
    $this->afternoonClose = $afternoonClose;

    return $this;
  }

  public function isIsClosed(): bool
  {
    return $this->isClosed;
  }

  public function setIsClosed(bool $isClosed): self
  {
    $this->isClosed = $isClosed;

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

  /**
   * Retourne les horaires formatés pour l'affichage
   */
  public function getFormattedHours(): string
  {
    if ($this->isClosed) {
      return 'Fermé';
    }

    $parts = [];

    if ($this->morningOpen && $this->morningClose) {
      $parts[] = $this->formatTime($this->morningOpen) . ' - ' . $this->formatTime($this->morningClose);
    }

    if ($this->afternoonOpen && $this->afternoonClose) {
      $parts[] = $this->formatTime($this->afternoonOpen) . ' - ' . $this->formatTime($this->afternoonClose);
    }

    return implode(' / ', $parts);
  }

  private function formatTime(\DateTimeInterface $time): string
  {
    return $time->format('H:i');
  }

  public function __toString(): string
  {
    return $this->getDayName() . ' : ' . $this->getFormattedHours();
  }
}

