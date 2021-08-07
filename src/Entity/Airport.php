<?php

namespace App\Entity;

use App\Repository\AirportRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

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
  private $name;

  /**
   * @ORM\Column(type="datetime_immutable")
   */
  private $CreatedAt;

  /**
   * @ORM\Column(type="datetime_immutable", nullable=true)
   */
  private $UpdatedAt;

  public function getId(): ?int {
    return $this->id;
  }

  public function getName(): ?string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getCreatedAt(): ?DateTimeImmutable {
    return $this->CreatedAt;
  }

  public function setCreatedAt(DateTimeImmutable $CreatedAt): self {
    $this->CreatedAt = $CreatedAt;

    return $this;
  }

  public function getUpdatedAt(): ?DateTimeImmutable {
    return $this->UpdatedAt;
  }

  public function setUpdatedAt(?DateTimeImmutable $UpdatedAt): self {
    $this->UpdatedAt = $UpdatedAt;

    return $this;
  }

  /**
   * @ORM\PrePersist
   */

  public function setCreatedTimestamp() {
    $this->setCreatedAt(new DateTimeImmutable('now'));

    return $this;
  }

  /**
   * @ORM\PreUpdate
   */

  public function setUpdatedTimestamp() {
    $this->setUpdatedAt(new DateTimeImmutable('now'));

    return $this;
  }
}