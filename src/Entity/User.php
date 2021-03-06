<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180, unique=true)
   */
  #[Assert\Email(
    message: 'The email {{ value }} is not a valid email.',
  )]
  private $email;

  /**
   * @ORM\Column(type="json")
   */
  private $roles = [];

  /**
   * @var string The hashed password
   * @ORM\Column(type="string")
   */
  private $password;

  /**
   * @ORM\Column(type="string", length=100, nullable=true)
   */
  #[Assert\Length(min: 3)]
  private $firstName;

  /**
   * @ORM\Column(type="string", length=100, nullable=true)
   */
  #[Assert\Length(min: 3)]
  private $lastName;

  /**
   * @ORM\Column(type="float", nullable=true)
   */
  #[Assert\Type('float')]
  #[Assert\Positive]
  private $balance;

  /**
   * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="user", orphanRemoval=true)
   */
  private $tickets;

  /**
   * @ORM\OneToMany(targetEntity=MoneyTransaction::class, mappedBy="user")
   */
  private $moneyTransactions;

  /**
   * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="user")
   */
  private $notifications;

  public function __construct() {
    $this->tickets = new ArrayCollection();
    $this->moneyTransactions = new ArrayCollection();
    $this->notifications = new ArrayCollection();
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getEmail(): ?string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string {
    return (string)$this->email;
  }

  /**
   * @deprecated since Symfony 5.3, use getUserIdentifier instead
   */
  public function getUsername(): string {
    return (string)$this->email;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  /**
   * Returning a salt is only needed, if you are not using a modern
   * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
   *
   * @see UserInterface
   */
  public function getSalt(): ?string {
    return null;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials() {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function getFirstName(): ?string {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): ?string {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self {
    $this->lastName = $lastName;

    return $this;
  }

  public function getBalance(): ?float {
    return $this->balance;
  }

  public function setBalance(?float $balance): self {
    $this->balance = $balance;

    return $this;
  }

  /**
   * @return Collection|Ticket[]
   */
  public function getTickets(): Collection {
    return $this->tickets;
  }

  public function addTicket(Ticket $ticket): self {
    if (!$this->tickets->contains($ticket)) {
      $this->tickets[] = $ticket;
      $ticket->setUser($this);
    }

    return $this;
  }

  public function removeTicket(Ticket $ticket): self {
    if ($this->tickets->removeElement($ticket)) {
      // set the owning side to null (unless already changed)
      if ($ticket->getUser() === $this) {
        $ticket->setUser(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|MoneyTransaction[]
   */
  public function getMoneyTransactions(): Collection
  {
      return $this->moneyTransactions;
  }

  public function addMoneyTransaction(MoneyTransaction $moneyTransaction): self
  {
      if (!$this->moneyTransactions->contains($moneyTransaction)) {
          $this->moneyTransactions[] = $moneyTransaction;
          $moneyTransaction->setUser($this);
      }

      return $this;
  }

  public function removeMoneyTransaction(MoneyTransaction $moneyTransaction): self
  {
      if ($this->moneyTransactions->removeElement($moneyTransaction)) {
          // set the owning side to null (unless already changed)
          if ($moneyTransaction->getUser() === $this) {
              $moneyTransaction->setUser(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection|Notification[]
   */
  public function getNotifications(): Collection
  {
      return $this->notifications;
  }

  public function addNotification(Notification $notification): self
  {
      if (!$this->notifications->contains($notification)) {
          $this->notifications[] = $notification;
          $notification->setUser($this);
      }

      return $this;
  }

  public function removeNotification(Notification $notification): self
  {
      if ($this->notifications->removeElement($notification)) {
          // set the owning side to null (unless already changed)
          if ($notification->getUser() === $this) {
              $notification->setUser(null);
          }
      }

      return $this;
  }
}