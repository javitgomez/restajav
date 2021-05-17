<?php

namespace App\Entity;

use App\Repository\CartDishRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartDishRepository::class)
 */
class CartDish
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
     * @ORM\Column(type="integer")
     */
    private $dto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sessionId;

    /**
     * @ORM\Column(type="integer")
     */
    private $dishId;

    public function __construct()
    {
        $this->setQuanty(0);
    }

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

    public function getDto(): ?int
    {
        return $this->dto;
    }

    public function setDto(int $dto): self
    {
        $this->dto = $dto;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function setDishId(int $id): self
    {
        $this->dishId = $id;

        return $this;
    }

    public function getDishId(): ?int
    {
        return $this->dishId;
    }
}
