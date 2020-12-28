<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private ?int $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     */
    private string $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     */
    private string $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"get"})
     */
    private string $lastName;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"get"})
     */
    private ?DateTimeInterface $registeredAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $user;

    public function __construct()
    {
        $this->registeredAt = new \DateTimeImmutable();
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getRegisteredAt(): ?DateTimeInterface
    {
        return $this->registeredAt;
    }

    /**
     * @param DateTimeInterface $registeredAt
     * @return $this
     */
    public function setRegisteredAt(DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }


}
