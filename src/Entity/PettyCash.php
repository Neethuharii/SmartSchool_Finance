<?php

namespace App\Entity;

use App\Repository\PettyCashRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PettyCashRepository::class)]
class PettyCash
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
    private ?string $source_event = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $collected_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $remitted_at = null;

    public function __construct()
    {
        $this->collected_at = new \DateTime(); 
        $this->status = 'Unremitted';          
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

    public function getSourceEvent(): ?string
    {
        return $this->source_event;
    }

    public function setSourceEvent(string $source_event): static
    {
        $this->source_event = $source_event;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getCollectedAt(): ?\DateTimeInterface
    {
        return $this->collected_at;
    }

    public function setCollectedAt(\DateTimeInterface $collected_at): static
    {
        $this->collected_at = $collected_at;

        return $this;
    }

    public function getRemittedAt(): ?\DateTimeInterface
    {
        return $this->remitted_at;
    }

    public function setRemittedAt(?\DateTimeInterface $remitted_at): static
    {
        $this->remitted_at = $remitted_at;

        return $this;
    }
}