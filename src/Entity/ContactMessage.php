<?php

namespace App\Entity;

use App\Repository\ContactMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactMessageRepository::class)]
#[ORM\Table(name: 'contact_message')]
#[ORM\HasLifecycleCallbacks]
class ContactMessage
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(type: 'string', length: 100)]
  private string $firstName;

  #[ORM\Column(type: 'string', length: 100)]
  private string $lastName;

  #[ORM\Column(type: 'string', length: 180)]
  private string $email;

  #[ORM\Column(type: 'string', length: 20, nullable: true)]
  private ?string $phone = null;

  #[ORM\Column(type: 'string', length: 150)]
  private string $subject;

  #[ORM\Column(type: 'text')]
  private string $message;

  #[ORM\Column(type: 'boolean')]
  private bool $isRead = false;

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

  public function getFirstName(): string
  {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self
  {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): string
  {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self
  {
    $this->lastName = $lastName;

    return $this;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getPhone(): ?string
  {
    return $this->phone;
  }

  public function setPhone(?string $phone): self
  {
    $this->phone = $phone;

    return $this;
  }

  public function getSubject(): string
  {
    return $this->subject;
  }

  public function setSubject(string $subject): self
  {
    $this->subject = $subject;

    return $this;
  }

  public function getMessage(): string
  {
    return $this->message;
  }

  public function setMessage(string $message): self
  {
    $this->message = $message;

    return $this;
  }

  public function isIsRead(): bool
  {
    return $this->isRead;
  }

  public function setIsRead(bool $isRead): self
  {
    $this->isRead = $isRead;

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

  public function getFullName(): string
  {
    return $this->firstName . ' ' . $this->lastName;
  }

  public function __toString(): string
  {
    return $this->subject . ' - ' . $this->getFullName();
  }
}

