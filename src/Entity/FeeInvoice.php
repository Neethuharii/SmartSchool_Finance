<?php

namespace App\Entity;

use App\Repository\FeeInvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeeInvoiceRepository::class)]
class FeeInvoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   
    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    
    #[ORM\ManyToOne(targetEntity: FeeCategory::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?FeeCategory $feeCategory = null;

    #[ORM\Column]
    private ?float $totalAmount = null;

    #[ORM\Column(length: 255)]
    private ?string $paidAmount = null;

    #[ORM\Column]
    private ?float $balanceAmount = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

  
    public function getFeeCategory(): ?FeeCategory
    {
        return $this->feeCategory;
    }

    public function setFeeCategory(?FeeCategory $feeCategory): static
    {
        $this->feeCategory = $feeCategory;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getPaidAmount(): ?string
    {
        return $this->paidAmount;
    }

    public function setPaidAmount(string $paidAmount): static
    {
        $this->paidAmount = $paidAmount;

        return $this;
    }

    public function getBalanceAmount(): ?float
    {
        return $this->balanceAmount;
    }

    public function setBalanceAmount(float $balanceAmount): static
    {
        $this->balanceAmount = $balanceAmount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}