<?php

namespace App\Entity;

use App\Repository\ExpenseClaimRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpenseClaimRepository::class)]
class ExpenseClaim
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   
#[ORM\ManyToOne(targetEntity: Teacher::class)]
#[ORM\JoinColumn(name: 'teacher_id', referencedColumnName: 'id')]
private ?Teacher $teacher_id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $receipt_path = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $submitted_at = null;

    
    public function __construct()
    {
        $this->submitted_at = new \DateTime();
        $this->status = 'Pending Review';      
    }

    public function getId(): ?int
    {
        return $this->id;
    }

   
    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

  
    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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

    public function getReceiptPath(): ?string
    {
        return $this->receipt_path;
    }

    public function setReceiptPath(?string $receipt_path): static
    {
        $this->receipt_path = $receipt_path;

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

    public function getSubmittedAt(): ?\DateTimeInterface
    {
        return $this->submitted_at;
    }

    public function setSubmittedAt(\DateTimeInterface $submitted_at): static
    {
        $this->submitted_at = $submitted_at;

        return $this;
    }
}