<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $id = null;

#[ORM\OneToOne(targetEntity: User::class, cascade: ['persist', 'remove'])]
#[ORM\JoinColumn(nullable: false)]
private ?User $user = null;

#[ORM\Column(length: 100)]
private ?string $first_name = null;

#[ORM\Column(length: 100)]
private ?string $last_name = null;

#[ORM\Column(length: 50)]
private ?string $staff_id = null;

#[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
private ?string $base_salary = null;

#[ORM\Column(length: 255)]
private ?string $status = null;

#[ORM\Column(length: 100, nullable: true)]
private ?string $bank_name = null;

#[ORM\Column(length: 50, nullable: true)]
private ?string $account_number = null;

#[ORM\Column(length: 20, nullable: true)]
private ?string $ifsc_code = null;

#[ORM\Column(length: 100, nullable: true)]
private ?string $branch_name = null;



public function getBankName(): ?string
{
return $this->bank_name;
}

public function setBankName(?string $bank_name): self
{
$this->bank_name = $bank_name;
return $this;
}

public function getAccountNumber(): ?string
{
return $this->account_number;
}

public function setAccountNumber(?string $account_number): self
{
$this->account_number = $account_number;
return $this;
}

public function getIfscCode(): ?string
{
return $this->ifsc_code;
}

public function setIfscCode(?string $ifsc_code): self
{
$this->ifsc_code = $ifsc_code;
return $this;
}

public function getBranchName(): ?string
{
return $this->branch_name;
}

public function setBranchName(?string $branch_name): self
{
$this->branch_name = $branch_name;
return $this;
}

public function getId(): ?int
{
return $this->id;
}
public function getUser(): ?User
{
return $this->user;
}

public function setUser(User $user): self
{
$this->user = $user;

return $this;
}
public function getFirstName(): ?string
{
return $this->first_name;
}

public function setFirstName(string $first_name): static
{
$this->first_name = $first_name;

return $this;
}

public function getLastName(): ?string
{
return $this->last_name;
}

public function setLastName(string $last_name): static
{
$this->last_name = $last_name;

return $this;
}

public function getStaffId(): ?string
{
return $this->staff_id;
}

public function setStaffId(string $staff_id): static
{
$this->staff_id = $staff_id;

return $this;
}

public function getBaseSalary(): ?string
{
return $this->base_salary;
}

public function setBaseSalary(string $base_salary): static
{
$this->base_salary = $base_salary;

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
}
