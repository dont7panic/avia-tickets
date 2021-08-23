<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Notification
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=100)
   */
  private $type;
  /**
   * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notifications")
   * @ORM\JoinColumn(nullable=false)
   */
  private $user;
  /**
   * @ORM\Column(type="text")
   */
  private $content;
  /**
   * @ORM\Column(type="string", length=100)
   */
  private $subject;
  /**
   * @ORM\Column(type="string", length=100)
   */
  private $status;
  /**
   * @ORM\ManyToOne(targetEntity=Ticket::class, inversedBy="notifications")
   */
  private $ticket;
  /**
   * @ORM\Column(type="datetime_immutable")
   */
  private $createdAt;
  /**
   * @ORM\Column(type="datetime_immutable", nullable=true)
   */
  private $updatedAt;

  /**
   * @ORM\PrePersist
   */

  public function setPrePersistValues(): void {
    $this->setCreatedAt(new DateTimeImmutable('now'));
  }

  /**
   * @ORM\PreUpdate
   */

  public function setUpdatedTimestamp(): void {
    $this->setUpdatedAt(new DateTimeImmutable('now'));
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getType(): ?string {
    return $this->type;
  }

  public function setType(string $type): self {
    $this->type = $type;

    return $this;
  }

  public function getUser(): ?User {
    return $this->user;
  }

  public function setUser(?User $user): self {
    $this->user = $user;

    return $this;
  }

  public function getContent(): ?string {
    return $this->content;
  }

  public function setContent(string $content): self {
    $this->content = $content;

    return $this;
  }

  public function getSubject(): ?string {
    return $this->subject;
  }

  public function setSubject(string $subject): self {
    $this->subject = $subject;

    return $this;
  }

  public function getStatus(): ?string {
    return $this->status;
  }

  public function setStatus(string $status): self {
    $this->status = $status;

    return $this;
  }

  public function getTicket(): ?Ticket {
    return $this->ticket;
  }

  public function setTicket(?Ticket $ticket): self {
    $this->ticket = $ticket;

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