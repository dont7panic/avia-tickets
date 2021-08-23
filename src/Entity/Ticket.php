<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Ticket
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tickets")
   * @ORM\JoinColumn(nullable=false)
   */
  private $user;

  /**
   * @ORM\ManyToOne(targetEntity=Flight::class, inversedBy="tickets")
   * @ORM\JoinColumn(nullable=false)
   */
  private $flight;

  /**
   * @ORM\Column(type="string", length=100)
   */
  #[Assert\Choice(['booked', 'paid'])]
  private $status;

  /**
   * @ORM\Column(type="datetime_immutable")
   */
  private $createdAt;

  /**
   * @ORM\Column(type="datetime_immutable", nullable=true)
   */
  private $updatedAt;

  /**
   * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="ticket")
   */
  private $notifications;

  public function __construct() {
    $this->notifications = new ArrayCollection();
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getUser(): ?User {
    return $this->user;
  }

  public function setUser(?User $user): self {
    $this->user = $user;

    return $this;
  }

  public function getFlight(): ?Flight {
    return $this->flight;
  }

  public function setFlight(?Flight $flight): self {
    $this->flight = $flight;

    return $this;
  }

  public function getStatus(): ?string {
    return $this->status;
  }

  public function setStatus(?string $status): self {
    $this->status = $status;

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
   * @ORM\PreRemove
   */

  public function clearNotifications(LifecycleEventArgs $args): void {
    foreach ($this->getNotifications() as $notification) {
      $this->removeNotification($notification);
    }
  }

  /**
   * @return Collection|Notification[]
   */
  public function getNotifications(): Collection {
    return $this->notifications;
  }

  public function removeNotification(Notification $notification): self {
    if ($this->notifications->removeElement($notification)) {
      // set the owning side to null (unless already changed)
      if ($notification->getTicket() === $this) {
        $notification->setTicket(null);
      }
    }

    return $this;
  }

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

  public function addNotification(Notification $notification): self {
    if (!$this->notifications->contains($notification)) {
      $this->notifications[] = $notification;
      $notification->setTicket($this);
    }

    return $this;
  }
}