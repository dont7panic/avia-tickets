<?php

namespace App\Entity;

use App\Repository\TestPlaneRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestPlaneRepository::class)
 */
class TestPlane
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

  public function getId(): ?int {
    return $this->id;
  }

  public function setId($id): self {
    $this->id = $id;

    return $this;
  }

  public function getName(): ?string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
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
}