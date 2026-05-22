<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[ORM\Table(name: 'students')]
class Student
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $id = null;

#[ORM\Column(length: 50, unique: true)]
private ?string $admission_no = null;

#[ORM\Column(length: 100)]
private ?string $first_name = null;

#[ORM\Column(length: 100)]
private ?string $last_name = null;

#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
private ?\DateTimeInterface $date_of_birth = null;

#[ORM\Column(length: 20)]
private ?string $contact_phone = null;

#[ORM\Column(length: 150, nullable: true)]
private ?string $contact_email = null;

#[ORM\Column(length: 20)]
private ?string $status = 'active';

#[ORM\ManyToOne(targetEntity: AcademicClass::class)]
private ?AcademicClass $academicClass = null; // Renamed to match your getter/setter
#[ORM\ManyToOne(targetEntity: Section::class)]
private ?Section $section = null;

#[ORM\Column(type: Types::DATETIME_MUTABLE)]
private ?\DateTimeInterface $created_at = null;

#[ORM\ManyToOne(targetEntity: User::class)]
#[ORM\JoinColumn(name: "parent_user_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
private ?User $parentUser = null;


#[ORM\OneToMany(mappedBy: 'student', targetEntity: StudentDiscount::class)]
private Collection $studentDiscounts;


#[ORM\OneToMany(mappedBy: 'student', targetEntity: FeePayment::class)]
private Collection $feePayments;
public function __construct()
{
$this->created_at = new \DateTime();
$this->feePayments = new ArrayCollection();

$this->studentDiscounts = new ArrayCollection();
}

// --- GETTERS AND SETTERS ---

public function getId(): ?int
{
return $this->id;
}

public function getAdmissionNo(): ?string
{
return $this->admission_no;
}

public function setAdmissionNo(string $admission_no): self
{
$this->admission_no = $admission_no;
return $this;
}

public function getFirstName(): ?string
{
return $this->first_name;
}

public function setFirstName(string $first_name): self
{
$this->first_name = $first_name;
return $this;
}

public function getLastName(): ?string
{
return $this->last_name;
}

public function setLastName(string $last_name): self
{
$this->last_name = $last_name;
return $this;
}

public function getContactPhone(): ?string
{
return $this->contact_phone;
}

public function setContactPhone(?string $contact_phone): self
{
    $this->contactPhone = $contact_phone;
    return $this;
}

public function getContactEmail(): ?string
{
return $this->contact_email;
}

public function setContactEmail(?string $contact_email): self
{
$this->contact_email = $contact_email;
return $this;
}

public function getStatus(): ?string
{
return $this->status;
}

public function setStatus(string $status): self
{
$this->status = $status;
return $this;
}

/**
 * @return Collection|StudentDiscount[]
 */
public function getStudentDiscounts(): Collection
{
return $this->studentDiscounts;
}


public function addStudentDiscount(StudentDiscount $studentDiscount): self
{
if (!$this->studentDiscounts->contains($studentDiscount)) {
$this->studentDiscounts[] = $studentDiscount;
$studentDiscount->setStudent($this);
}
return $this;
}

public function getSection(): ?Section
{
return $this->section;
}

public function setSection(?Section $section): self
{
$this->section = $section;
return $this;
}

public function getParentUser(): ?User
    {
        return $this->parentUser;
    }

    public function setParentUser(?User $parentUser): self
    {
        $this->parentUser = $parentUser;

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
public function getRemainingBalance(float $currentTermFee = 1200.00): float
{

$discountAmount = 0;


if ($this->studentDiscounts) {
foreach ($this->studentDiscounts as $assignment) {
if ($assignment->getStatus() === 'Active') {
$discount = $assignment->getDiscount();
if ($discount->getType() === 'percentage') {
$discountAmount = $currentTermFee * ($discount->getValue() / 100);
} else {
$discountAmount = $discount->getValue();
}
break; 
}
}
}

$netOwed = $currentTermFee - $discountAmount;


$totalPaid = 0;


if ($this->feePayments) {
foreach ($this->feePayments as $payment) {
$totalPaid += $payment->getAmount();
}
}

return $netOwed - $totalPaid;
}
}