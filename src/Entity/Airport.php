<?php

namespace App\Entity;

use App\Repository\AirportRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AirportRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Airport
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  #[Assert\Length(min: 3)]
  private $name;

  /**
   * @ORM\Column(type="datetime_immutable")
   */
  private $createdAt;

  /**
   * @ORM\Column(type="datetime_immutable", nullable=true)
   */
  private $updatedAt;

  /**
   * @ORM\OneToMany(targetEntity=Flight::class, mappedBy="airportFrom")
   */
  private $flightsOut;

  /**
   * @ORM\OneToMany(targetEntity=Flight::class, mappedBy="airportTo")
   */
  private $flightsIn;

  public function __construct() {
    $this->flightsOut = new ArrayCollection();
    $this->flightsIn = new ArrayCollection();
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getCreatedAt(): ?DateTimeImmutable {
    return $this->createdAt;
  }

  public function setCreatedAt(DateTimeImmutable $createdAt): self {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getUpdatedAt(): ?DateTimeImmutable {
    return $this->updatedAt;
  }

  public function setUpdatedAt(?DateTimeImmutable $updatedAt): self {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  /**
   * @ORM\PrePersist
   */

  public function setCreatedTimestamp(): void {
    $this->setCreatedAt(new DateTimeImmutable('now'));
  }

  /**
   * @ORM\PreUpdate
   */

  public function setUpdatedTimestamp(): void {
    $this->setUpdatedAt(new DateTimeImmutable('now'));
  }

  /**
   * @return Collection|Flight[]
   */
  public function getFlightsOut(): Collection {
    return $this->flightsOut;
  }

  public function addFlightsOut(Flight $flightsOut): self {
    if (!$this->flightsOut->contains($flightsOut)) {
      $this->flightsOut[] = $flightsOut;
      $flightsOut->setAirportFrom($this);
    }

    return $this;
  }

  public function removeFlightsOut(Flight $flightsOut): self {
    if ($this->flightsOut->removeElement($flightsOut)) {
      // set the owning side to null (unless already changed)
      if ($flightsOut->getAirportFrom() === $this) {
        $flightsOut->setAirportFrom(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|Flight[]
   */
  public function getFlightsIn(): Collection {
    return $this->flightsIn;
  }

  public function addFlightsIn(Flight $flightsIn): self {
    if (!$this->flightsIn->contains($flightsIn)) {
      $this->flightsIn[] = $flightsIn;
      $flightsIn->setAirportTo($this);
    }

    return $this;
  }

  public function removeFlightsIn(Flight $flightsIn): self {
    if ($this->flightsIn->removeElement($flightsIn)) {
      // set the owning side to null (unless already changed)
      if ($flightsIn->getAirportTo() === $this) {
        $flightsIn->setAirportTo(null);
      }
    }

    return $this;
  }

  public function __toString(): string {
    return $this->getName();
  }

  public function getName(): ?string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }
}