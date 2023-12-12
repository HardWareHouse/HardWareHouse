<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
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
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'categorieId')]
    #[ORM\JoinColumn(name: "entreprise", referencedColumnName: "id")]
    private ?Entreprise $entrepriseId = null;

    #[ORM\OneToMany(mappedBy: 'categorieId', targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: "produit", referencedColumnName: "id")]
    private Collection $produitId;

    public function __construct()
    {
        $this->produitId = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Produit>
     */
    public function getProduitId(): Collection
    {
        return $this->produitId;
    }

    public function addProduitId(Produit $produitId): static
    {
        if (!$this->produitId->contains($produitId)) {
            $this->produitId->add($produitId);
            $produitId->setCategorieId($this);
        }

        return $this;
    }

    public function removeProduitId(Produit $produitId): static
    {
        if ($this->produitId->removeElement($produitId)) {
            // set the owning side to null (unless already changed)
            if ($produitId->getCategorieId() === $this) {
                $produitId->setCategorieId(null);
            }
        }

        return $this;
    }
}