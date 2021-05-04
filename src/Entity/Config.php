<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberPhotoGallery;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberPhotoGallery(): ?int
    {
        return $this->numberPhotoGallery;
    }

    public function setNumberPhotoGallery(?int $numberPhotoGallery): self
    {
        $this->numberPhotoGallery = $numberPhotoGallery;

        return $this;
    }
}
