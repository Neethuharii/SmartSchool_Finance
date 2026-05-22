<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $academic_class_id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $subject_code = null;


#[ORM\ManyToOne(targetEntity: AcademicClass::class)]
#[ORM\JoinColumn(nullable: false)]
private ?AcademicClass $academicClass = null; 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcademicClassId(): ?int
    {
        return $this->academic_class_id;
    }

    public function setAcademicClassId(int $academic_class_id): static
    {
        $this->academic_class_id = $academic_class_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSubjectCode(): ?string
    {
        return $this->subject_code;
    }

    public function setSubjectCode(string $subject_code): static
    {
        $this->subject_code = $subject_code;

        return $this;
    }
    
    

public function getAcademicClass(): ?AcademicClass
{
    return $this->academicClass;
}

public function setAcademicClass(?AcademicClass $academicClass): self
{
    $this->academicClass = $academicClass;
    return $this;
}

/**
     * @return Collection<int, Syllabus>
     */
    public function getSyllabuses(): Collection
    {
        return $this->syllabuses;
    }

    public function addSyllabus(Syllabus $syllabus): self
    {
        if (!$this->syllabuses->contains($syllabus)) {
            $this->syllabuses->add($syllabus);
            $syllabus->setSubject($this);
        }

        return $this;
    }

    public function removeSyllabus(Syllabus $syllabus): self
    {
        if ($this->syllabuses->removeElement($syllabus)) {
            // set the owning side to null (unless already changed)
            if ($syllabus->getSubject() === $this) {
                $syllabus->setSubject(null);
            }
        }

        return $this;
    }
}
