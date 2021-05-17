<?php

namespace App\Entity;

use App\Repository\DishRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DishRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Dish
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="float")
     */
    private $prize;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $allergen = '';

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="dish")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Aggregate::class, mappedBy="dish")
     */
    private $aggregate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;
    
    public function __construct()
    {
        $this->aggregate = new ArrayCollection();
        // by defaut all dish is not published
        $this->setPublished(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getPrize(): ?float
    {
        return $this->prize;
    }

    public function setPrize($prize): self
    {
        $this->prize = floatval($prize);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): void
    {
        $this->updatedAt =  new \DateTimeImmutable();
    }

    public function getAllergen(): ?string
    {
        return $this->allergen;
    }

    public function setAllergen(?string $allergen): self
    {
        $this->allergen = $allergen;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Aggregate[]
     */
    public function getAggregate(): Collection
    {
        return $this->aggregate;
    }

    public function addAggregate(Aggregate $aggregate): self
    {
        if (!$this->aggregate->contains($aggregate)) {
            $this->aggregate[] = $aggregate;
            $aggregate->setDish($this);
        }

        return $this;
    }

    public function removeAggregate(Aggregate $aggregate): self
    {
        if ($this->aggregate->removeElement($aggregate)) {
            // set the owning side to null (unless already changed)
            if ($aggregate->getDish() === $this) {
                $aggregate->setDish(null);
            }
        }

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }
}
