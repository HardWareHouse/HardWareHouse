<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    private ?Entreprise $entreprise = null;

    #[ORM\OneToMany(mappedBy: 'categorieId', targetEntity: Produit::class)]
    private Collection $produitId;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->produitId = new ArrayCollection();
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;
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
