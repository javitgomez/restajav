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
     * @ORM\ManyToMany(targetEntity=Dish::class)
     */
    private $dish;

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

    public function __construct()
    {
        $this->dish = new ArrayCollection();
        $this->setQuanty(0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Dish[]
     */
    public function getDish(): Collection
    {
        return $this->dish;
    }

    public function addDish(Dish $dish): self
    {
        if (!$this->dish->contains($dish)) {
            $this->dish[] = $dish;
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        $this->dish->removeElement($dish);

        return $this;
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
}
