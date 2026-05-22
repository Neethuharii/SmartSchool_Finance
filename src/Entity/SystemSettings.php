<?php

namespace App\Entity;

use App\Repository\SystemSettingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemSettingsRepository::class)]
class SystemSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $schoolName = null;

    #[ORM\Column(length: 10)]
    private ?string $currency = null;

    #[ORM\Column]
    private ?float $taxPercentage = null;

    #[ORM\Column(length: 255)]
    private ?string $receiptPrefix = null;

    #[ORM\Column(length: 20)]
    private ?string $invoicePrefix = null;

    #[ORM\Column(length: 20)]
    private ?string $logo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $lateFeeAmount = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $emailFrom = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchoolName(): ?string
    {
        return $this->schoolName;
    }

    public function setSchoolName(string $schoolName): static
    {
        $this->schoolName = $schoolName;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getTaxPercentage(): ?float
    {
        return $this->taxPercentage;
    }

    public function setTaxPercentage(float $taxPercentage): static
    {
        $this->taxPercentage = $taxPercentage;

        return $this;
    }

    public function getReceiptPrefix(): ?string
    {
        return $this->receiptPrefix;
    }

    public function setReceiptPrefix(string $receiptPrefix): static
    {
        $this->receiptPrefix = $receiptPrefix;

        return $this;
    }

    public function getInvoicePrefix(): ?string
    {
        return $this->invoicePrefix;
    }

    public function setInvoicePrefix(string $invoicePrefix): static
    {
        $this->invoicePrefix = $invoicePrefix;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getLateFeeAmount(): ?string
    {
        return $this->lateFeeAmount;
    }

    public function setLateFeeAmount(string $lateFeeAmount): static
    {
        $this->lateFeeAmount = $lateFeeAmount;

        return $this;
    }

    public function getEmailFrom(): ?string
    {
        return $this->emailFrom;
    }

    public function setEmailFrom(?string $emailFrom): static
    {
        $this->emailFrom = $emailFrom;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
