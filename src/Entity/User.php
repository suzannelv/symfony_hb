<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', message:'L\'email {{ value }} existe déjà.')]

class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le champs de mail ne peut pas être vide')]
    #[Assert\Email(message: 'Le format n\'est pas correct')]
    #[Assert\Length(min:2, max:255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $subscribed = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $subscriptionDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isSubscribed(): ?bool
    {
        return $this->subscribed;
    }

    public function setSubscribed(bool $subscribed): static
    {
        $this->subscribed = $subscribed;

        return $this;
    }

    public function getSubscriptionDate(): ?\DateTimeInterface
    {
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate(\DateTimeInterface $subscriptionDate): static
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }
}
