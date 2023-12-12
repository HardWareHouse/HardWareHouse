<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePaiement = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $methodePaiement = null;

    #[ORM\ManyToOne(inversedBy: 'paiementId')]
    private ?Facture $factureId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(\DateTimeInterface $datePaiement): static
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getMethodePaiement(): ?string
    {
        return $this->methodePaiement;
    }

    public function setMethodePaiement(string $methodePaiement): static
    {
        $this->methodePaiement = $methodePaiement;

        return $this;
    }

    public function getFactureId(): ?Facture
    {
        return $this->factureId;
    }

    public function setFactureId(?Facture $factureId): static
    {
        $this->factureId = $factureId;

        return $this;
    }
}