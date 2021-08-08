<?php

namespace App\Entity;

use App\Repository\PlaneRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PlaneRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Plane
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
   * @ORM\Column(type="integer")
   */
  private $seats;

  /**
   * @ORM\Column(type="datetime_immutable")
   */
  private $createdAt;

  /**
   * @ORM\Column(type="datetime_immutable", nullable=true)
   */
  private $updatedAt;

  /**
   * @ORM\OneToMany(targetEntity=Flight::class, mappedBy="plane")
   */
  private $flights;

  public function __construct() {
    $this->flights = new ArrayCollection();
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getSeats(): ?int {
    return $this->seats;
  }

  public function setSeats(int $seats): self {
    $this->seats = $seats;

    return $this;
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
  public function getFlights(): Collection {
    return $this->flights;
  }

  public function addFlight(Flight $flight): self {
    if (!$this->flights->contains($flight)) {
      $this->flights[] = $flight;
      $flight->setPlane($this);
    }

    return $this;
  }

  public function removeFlight(Flight $flight): self {
    if ($this->flights->removeElement($flight)) {
      // set the owning side to null (unless already changed)
      if ($flight->getPlane() === $this) {
        $flight->setPlane(null);
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