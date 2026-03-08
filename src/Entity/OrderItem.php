<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
#[ORM\Table(name: 'order_item')]
class OrderItem
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Order $orderItem = null;

  #[ORM\ManyToOne(targetEntity: Dish::class)]
  #[ORM\JoinColumn(nullable: false)]
  private ?Dish $dish = null;

  #[ORM\Column(type: 'string', length: 150)]
  private string $dishName;

  #[ORM\Column(type: 'decimal', precision: 6, scale: 2)]
  private string $unitPrice = '0.00';

  #[ORM\Column(type: 'integer')]
  private int $quantity = 1;

  #[ORM\Column(type: 'text', nullable: true)]
  private ?string $specialInstructions = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getOrderItem(): ?Order
  {
    return $this->orderItem;
  }

  public function setOrderItem(?Order $orderItem): self
  {
    $this->orderItem = $orderItem;
    return $this;
  }

  public function getDish(): ?Dish
  {
    return $this->dish;
  }

  public function setDish(?Dish $dish): self
  {
    $this->dish = $dish;
    return $this;
  }

  public function getDishName(): string
  {
    return $this->dishName;
  }

  public function setDishName(string $dishName): self
  {
    $this->dishName = $dishName;
    return $this;
  }

  public function getUnitPrice(): string
  {
    return $this->unitPrice;
  }

  public function setUnitPrice(string $unitPrice): self
  {
    $this->unitPrice = $unitPrice;
    return $this;
  }

  public function getQuantity(): int
  {
    return $this->quantity;
  }

  public function setQuantity(int $quantity): self
  {
    $this->quantity = $quantity;
    return $this;
  }

  public function getSpecialInstructions(): ?string
  {
    return $this->specialInstructions;
  }

  public function setSpecialInstructions(?string $specialInstructions): self
  {
    $this->specialInstructions = $specialInstructions;
    return $this;
  }

  /**
   * Calcule le sous-total pour cet article
   */
  public function getSubtotal(): float
  {
    return $this->quantity * (float) $this->unitPrice;
  }
}

