<?php

namespace App\Entity;

use App\Repository\FlightRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Flight
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity=Airport::class, inversedBy="flightsOut")
   * @ORM\JoinColumn(nullable=false)
   */
  #[Assert\NotBlank]
  #[Assert\NotEqualTo(propertyPath: "airportTo")]
  private $airportFrom;

  /**
   * @ORM\ManyToOne(targetEntity=Airport::class, inversedBy="flightsIn")
   * @ORM\JoinColumn(nullable=false)
   */
  #[Assert\NotBlank]
  #[Assert\NotEqualTo(propertyPath: "airportFrom")]
  private $airportTo;

  /**
   * @ORM\ManyToOne(targetEntity=Plane::class, inversedBy="flights")
   * @ORM\JoinColumn(nullable=false)
   */
  #[Assert\NotBlank]
  private $plane;

  /**
   * @ORM\Column(type="datetime_immutable")
   */
  #[Assert\GreaterThan('now')]
  private $departsAt;

  /**
   * @ORM\Column(type="datetime_immutable")
   */
  private $arrivesAt;

  /**
   * @ORM\Column(type="datetime_immutable")
   */
  private $createdAt;

  /**
   * @ORM\Column(type="datetime_immutable", nullable=true)
   */
  private $updatedAt;

  public function getId(): ?int {
    return $this->id;
  }

  public function getAirportFrom(): ?Airport {
    return $this->airportFrom;
  }

  public function setAirportFrom(?Airport $airportFrom): self {
    $this->airportFrom = $airportFrom;

    return $this;
  }

  public function getAirportTo(): ?Airport {
    return $this->airportTo;
  }

  public function setAirportTo(?Airport $airportTo): self {
    $this->airportTo = $airportTo;

    return $this;
  }

  public function getPlane(): ?Plane {
    return $this->plane;
  }

  public function setPlane(?Plane $plane): self {
    $this->plane = $plane;

    return $this;
  }

  public function getArrivesAt(): ?DateTimeImmutable {
    return $this->arrivesAt;
  }

  public function setArrivesAt(DateTimeImmutable $arrivesAt): self {
    $this->arrivesAt = $arrivesAt;

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

  public function setPrePersistValues(): void {
    $this->setCreatedAt(new DateTimeImmutable('now'));

    $arrivesAt = $this->getDepartsAt()->add(new DateInterval('PT4H'))->format('Y-m-d H:i:s');
    $this->setArrivesAt(new DateTimeImmutable($arrivesAt));
  }

  public function getDepartsAt(): ?DateTimeImmutable {
    return $this->departsAt;
  }

  public function setDepartsAt(DateTimeImmutable $departsAt): self {
    $this->departsAt = $departsAt;

    return $this;
  }

  /**
   * @ORM\PreUpdate
   */

  public function setUpdatedTimestamp(): void {
    $this->setUpdatedAt(new DateTimeImmutable('now'));
  }

}