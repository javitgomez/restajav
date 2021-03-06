<?php

namespace App\Entity;

use App\Repository\SurveyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SurveyRepository::class)
 */
class Survey
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
    private $valoration;

    /**
     * @ORM\Column(type="integer")
     */
    private $satisfaction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="survey")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    public function __construct()
    {
        $this->setPublic(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValoration(): ?int
    {
        return $this->valoration;
    }

    public function setValoration(int $valoration): self
    {
        $this->valoration = $valoration;

        return $this;
    }

    public function getSatisfaction(): ?int
    {
        return $this->satisfaction;
    }

    public function setSatisfaction(int $satisfaction): self
    {
        $this->satisfaction = $satisfaction;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }
}
