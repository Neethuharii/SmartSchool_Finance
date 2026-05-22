<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
class Section
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

 #[ORM\ManyToOne(targetEntity: AcademicClass::class, inversedBy: 'sections')]
    #[ORM\JoinColumn(name: 'class_id', referencedColumnName: 'id', nullable: false)] // <-- ADD THIS LINE
    private ?AcademicClass $academicClass = null;
    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }

    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getAcademicClass(): ?AcademicClass { return $this->academicClass; }

    public function setAcademicClass(?AcademicClass $academicClass): self
    {
        $this->academicClass = $academicClass;
        return $this;
    }
}