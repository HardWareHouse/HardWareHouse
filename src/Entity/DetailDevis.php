<?php

namespace App\Entity;

use App\Repository\DetailDevisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailDevisRepository::class)]
class DetailDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $remise = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\OneToOne(mappedBy: 'detailDevisId', cascade: ['persist', 'remove'])]
    private ?Devis $devisId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getRemise(): ?int
    {
        return $this->remise;
    }

    public function setRemise(int $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getDevisId(): ?Devis
    {
        return $this->devisId;
    }

    public function setDevisId(?Devis $devisId): static
    {
        // unset the owning side of the relation if necessary
        if ($devisId === null && $this->devisId !== null) {
            $this->devisId->setDetailDevisId(null);
        }

        // set the owning side of the relation if necessary
        if ($devisId !== null && $devisId->getDetailDevisId() !== $this) {
            $devisId->setDetailDevisId($this);
        }

        $this->devisId = $devisId;

        return $this;
    }
}
