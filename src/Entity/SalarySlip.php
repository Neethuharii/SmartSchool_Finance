<?php

namespace App\Entity;

use App\Repository\SalarySlipRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalarySlipRepository::class)]
class SalarySlip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

#[ORM\ManyToOne(targetEntity: Teacher::class)]
#[ORM\JoinColumn(name: 'teacher_id', referencedColumnName: 'id')]
private ?Teacher $teacher_id = null;

    #[ORM\Column(length: 100)]
    private ?string $month = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $basic_pay = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $deductions = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $net_pay = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $payment_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

  public function getTeacherId(): ?Teacher
    {
        return $this->teacher_id;
    }

    public function setTeacherId(?Teacher $teacher): static
    {
        $this->teacher_id = $teacher;

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getBasicPay(): ?string
    {
        return $this->basic_pay;
    }

    public function setBasicPay(string $basic_pay): static
    {
        $this->basic_pay = $basic_pay;

        return $this;
    }

    public function getDeductions(): ?string
    {
        return $this->deductions;
    }

    public function setDeductions(string $deductions): static
    {
        $this->deductions = $deductions;

        return $this;
    }

    public function getNetPay(): ?string
    {
        return $this->net_pay;
    }

    public function setNetPay(string $net_pay): static
    {
        $this->net_pay = $net_pay;

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

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(\DateTimeInterface $payment_date): static
    {
        $this->payment_date = $payment_date;

        return $this;
    }
}
