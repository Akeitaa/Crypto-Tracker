<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Vous devez sélectionner une crypto')]
    private string $crypto;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Vous devez renseigner le prix d\'achat')]
    private float $price;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Vous devez renseigner la quantité achetée')]
    private float $quantity;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrypto(): string
    {
        return $this->crypto;
    }

    public function setCrypto(?string $crypto): self
    {
        $this->crypto = $crypto;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
