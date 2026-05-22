<?php

namespace App\Entity;

use App\Repository\BankTransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankTransactionRepository::class)]
class BankTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $referenceId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transactionDate = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matchedRecord = null;

    #[ORM\Column(nullable: true)]
    private ?int $confidenceScore = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }

    public function setReferenceId(string $referenceId): static
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getTransactionDate(): ?\DateTimeInterface
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(\DateTimeInterface $transactionDate): static
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMatchedRecord(): ?string
    {
        return $this->matchedRecord;
    }

    public function setMatchedRecord(?string $matchedRecord): static
    {
        $this->matchedRecord = $matchedRecord;

        return $this;
    }

    public function getConfidenceScore(): ?int
    {
        return $this->confidenceScore;
    }

    public function setConfidenceScore(?int $confidenceScore): static
    {
        $this->confidenceScore = $confidenceScore;

        return $this;
    }
}
