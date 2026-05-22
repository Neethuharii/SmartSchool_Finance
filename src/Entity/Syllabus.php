<?php

namespace App\Entity;

use App\Repository\SyllabusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SyllabusRepository::class)]
class Syllabus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $subject_id = null;

    #[ORM\Column(length: 100)]
    private ?string $chapter_name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Subject::class, inversedBy: 'syllabuses')]
#[ORM\JoinColumn(nullable: false)]
private ?Subject $subject = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubjectId(): ?int
    {
        return $this->subject_id;
    }

    public function setSubjectId(int $subject_id): static
    {
        $this->subject_id = $subject_id;

        return $this;
    }

    public function getChapterName(): ?string
    {
        return $this->chapter_name;
    }

    public function setChapterName(string $chapter_name): static
    {
        $this->chapter_name = $chapter_name;

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
}
