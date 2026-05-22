<?php

namespace App\Entity;

use App\Repository\AcademicClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcademicClassRepository::class)]
class AcademicClass
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $id = null;

#[ORM\Column(length: 100, nullable: true)]
private ?string $name = null;

#[ORM\OneToMany(mappedBy: 'academicClass', targetEntity: Section::class, orphanRemoval: true)]
private Collection $sections;

#[ORM\Column]
private ?\DateTimeImmutable $created_at = null;

public function __construct()
{
$this->sections = new ArrayCollection();
$this->created_at = new \DateTimeImmutable();
}




public function getId(): ?int
{
return $this->id;
}

public function getName(): ?string
{
return $this->name;
}

public function setName(?string $name): static
{
$this->name = $name;

return $this;
}

/**
 * @return Collection<int, Section>
 */
public function getSections(): Collection
{
return $this->sections;
}

public function addSection(Section $section): static
{
if (!$this->sections->contains($section)) {
$this->sections->add($section);
$section->setNo($this);
}

return $this;
}

public function removeSection(Section $section): static
{
if ($this->sections->removeElement($section)) {
// set the owning side to null (unless already changed)
if ($section->getNo() === $this) {
$section->setNo(null);
}
}

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
