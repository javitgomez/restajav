<?php

namespace App\Entity;

use App\Repository\CustomManagerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomManagerRepository::class)
 */
class CustomManager
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $calendar;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $photoDescription;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $openHour;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $callPhone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $googleMapsFrame;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCalendar(): ?string
    {
        return $this->calendar;
    }

    public function setCalendar(?string $calendar): self
    {
        $this->calendar = $calendar;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhotoDescription(): ?string
    {
        return $this->photoDescription;
    }

    public function setPhotoDescription(?string $photoDescription): self
    {
        $this->photoDescription = $photoDescription;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getOpenHour(): ?string
    {
        return $this->openHour;
    }

    public function setOpenHour(?string $openHour): self
    {
        $this->openHour = $openHour;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCallPhone(): ?string
    {
        return $this->callPhone;
    }

    public function setCallPhone(?string $callPhone): self
    {
        $this->callPhone = $callPhone;

        return $this;
    }

    public function getGoogleMapsFrame(): ?string
    {
        return $this->googleMapsFrame;
    }

    public function setGoogleMapsFrame(?string $googleMapsFrame): self
    {
        $this->googleMapsFrame = $googleMapsFrame;

        return $this;
    }
}
