<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderItemRepository::class)
 * @ORM\Table(name="`orderItem`")
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quanty;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderCustomer;

    /**
     * @ORM\ManyToOne(targetEntity=Dish::class, inversedBy="orderItem")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dish;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getQuanty(): ?int
    {
        return $this->quanty;
    }

    public function setQuanty(int $quanty): self
    {
        $this->quanty = $quanty;

        return $this;
    }

    public function getOrderCustomer(): ?Order
    {
        return $this->orderCustomer;
    }

    public function setOrderCustomer(?Order $orderCustomer): self
    {
        $this->orderCustomer = $orderCustomer;

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
}
