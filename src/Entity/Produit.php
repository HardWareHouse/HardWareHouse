<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'produitId')]
    private ?Entreprise $entrepriseId = null;

    #[ORM\ManyToOne(inversedBy: 'produitId')]
    private ?Categorie $categorieId = null;

    #[ORM\ManyToOne(inversedBy: 'produitId')]
    private ?Devis $devisId = null;

    #[ORM\ManyToOne(inversedBy: 'produitId')]
    private ?Facture $factureId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

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

    public function getEntrepriseId(): ?Entreprise
    {
        return $this->entrepriseId;
    }

    public function setEntrepriseId(?Entreprise $entrepriseId): static
    {
        $this->entrepriseId = $entrepriseId;

        return $this;
    }

    public function getCategorieId(): ?Categorie
    {
        return $this->categorieId;
    }

    public function setCategorieId(?Categorie $categorieId): static
    {
        $this->categorieId = $categorieId;

        return $this;
    }

    public function getDevisId(): ?Devis
    {
        return $this->devisId;
    }

    public function setDevisId(?Devis $devisId): static
    {
        $this->devisId = $devisId;

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