<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\HasLifecycleCallbacks]
class Order
{
  public const STATUS_PENDING = 'pending';
  public const STATUS_CONFIRMED = 'confirmed';
  public const STATUS_PREPARING = 'preparing';
  public const STATUS_READY = 'ready';
  public const STATUS_COMPLETED = 'completed';
  public const STATUS_CANCELLED = 'cancelled';

  public const TYPE_PICKUP = 'pickup';
  public const TYPE_DELIVERY = 'delivery';

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(type: 'string', length: 20)]
  private string $status = self::STATUS_PENDING;

  #[ORM\Column(type: 'string', length: 20)]
  private string $type = self::TYPE_PICKUP;

  #[ORM\Column(type: 'string', length: 150)]
  private string $customerName = '';

  #[ORM\Column(type: 'string', length: 20)]
  private string $customerPhone = '';

  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  private ?string $customerEmail = null;

  #[ORM\Column(type: 'text', nullable: true)]
  private ?string $deliveryAddress = null;

  #[ORM\Column(type: 'text', nullable: true)]
  private ?string $comments = null;

  #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
  private string $totalAmount = '0.00';

  #[ORM\OneToMany(mappedBy: 'orderItem', targetEntity: OrderItem::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
  #[ORM\OrderBy(['id' => 'ASC'])]
  private Collection $items;

  #[ORM\Column(type: 'datetime')]
  private \DateTimeInterface $createdAt;

  #[ORM\Column(type: 'datetime', nullable: true)]
  private ?\DateTimeInterface $updatedAt = null;

  public function __construct()
  {
    $this->items = new ArrayCollection();
    $this->createdAt = new \DateTimeImmutable();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getStatus(): string
  {
    return $this->status;
  }

  public function setStatus(string $status): self
  {
    $this->status = $status;
    return $this;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function setType(string $type): self
  {
    $this->type = $type;
    return $this;
  }

  public function getCustomerName(): string
  {
    return $this->customerName;
  }

  public function setCustomerName(string $customerName): self
  {
    $this->customerName = $customerName;
    return $this;
  }

  public function getCustomerPhone(): string
  {
    return $this->customerPhone;
  }

  public function setCustomerPhone(string $customerPhone): self
  {
    $this->customerPhone = $customerPhone;
    return $this;
  }

  public function getCustomerEmail(): ?string
  {
    return $this->customerEmail;
  }

  public function setCustomerEmail(?string $customerEmail): self
  {
    $this->customerEmail = $customerEmail;
    return $this;
  }

  public function getDeliveryAddress(): ?string
  {
    return $this->deliveryAddress;
  }

  public function setDeliveryAddress(?string $deliveryAddress): self
  {
    $this->deliveryAddress = $deliveryAddress;
    return $this;
  }

  public function getComments(): ?string
  {
    return $this->comments;
  }

  public function setComments(?string $comments): self
  {
    $this->comments = $comments;
    return $this;
  }

  public function getTotalAmount(): string
  {
    return $this->totalAmount;
  }

  public function setTotalAmount(string $totalAmount): self
  {
    $this->totalAmount = $totalAmount;
    return $this;
  }

  /**
   * @return Collection<int, OrderItem>
   */
  public function getItems(): Collection
  {
    return $this->items;
  }

  public function addItem(OrderItem $item): self
  {
    if (!$this->items->contains($item)) {
      $this->items->add($item);
      $item->setOrderItem($this);
    }
    return $this;
  }

  public function removeItem(OrderItem $item): self
  {
    if ($this->items->removeElement($item)) {
      if ($item->getOrderItem() === $this) {
        $item->setOrderItem(null);
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

  /**
   * Calcule le total de la commande
   */
  public function calculateTotal(): float
  {
    $total = 0;
    foreach ($this->items as $item) {
      $total += $item->getQuantity() * (float) $item->getUnitPrice();
    }
    return $total;
  }

  /**
   * Retourne le nombre total d'articles
   */
  public function getTotalItems(): int
  {
    $total = 0;
    foreach ($this->items as $item) {
      $total += $item->getQuantity();
    }
    return $total;
  }

  public function __toString(): string
  {
    return 'Commande #' . $this->id;
  }
}

