<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $informationFiscale = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'userUuid')]
    #[ORM\JoinColumn(name: "user_uuid", referencedColumnName: "uuid", nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Client::class)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Produit::class)]
    private Collection $produits;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Categorie::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Devis::class)]
    private Collection $devis;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Facture::class)]
    private Collection $factures;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->clients = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;
        return $this;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

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

    public function getInformationFiscale(): ?string
    {
        return $this->informationFiscale;
    }

    public function setInformationFiscale(string $informationFiscale): static
    {
        $this->informationFiscale = $informationFiscale;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $clients): static
    {
        if (!$this->clients->contains($clients)) {
            $this->clients->add($clients);
            $clients->setEntreprise($this);
        }

        return $this;
    }

    public function removeClient(Client $clients): static
    {
        if ($this->clients->removeElement($clients)) {
            // set the owning side to null (unless already changed)
            if ($clients->getEntreprise() === $this) {
                $clients->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduitId(): Collection
    {
        return $this->produits;
    }

    public function addProduitId(Produit $produits): static
    {
        if (!$this->produits->contains($produits)) {
            $this->produits->add($produits);
            $produits->setEntreprise($this);
        }

        return $this;
    }

    public function removeProduitId(Produit $produits): static
    {
        if ($this->produits->removeElement($produits)) {
            // set the owning side to null (unless already changed)
            if ($produits->getEntreprise() === $this) {
                $produits->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategorieId(): Collection
    {
        return $this->categories;
    }

    public function addCategorieId(Categorie $categories): static
    {
        if (!$this->categories->contains($categories)) {
            $this->categories->add($categories);
            $categories->setEntreprise($this);
        }

        return $this;
    }

    public function removeCategorieId(Categorie $categories): static
    {
        if ($this->categories->removeElement($categories)) {
            // set the owning side to null (unless already changed)
            if ($categories->getEntreprise() === $this) {
                $categories->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevisId(): Collection
    {
        return $this->devis;
    }

    public function addDevisId(Devis $devis): static
    {
        if (!$this->devis->contains($devis)) {
            $this->devis->add($devis);
            $devis->setEntreprise($this);
        }

        return $this;
    }

    public function removeDevisId(Devis $devis): static
    {
        if ($this->devis->removeElement($devis)) {
            // set the owning side to null (unless already changed)
            if ($devis->getEntreprise() === $this) {
                $devis->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactureId(): Collection
    {
        return $this->factures;
    }

    public function addFactureId(Facture $factures): static
    {
        if (!$this->factures->contains($factures)) {
            $this->factures->add($factures);
            $factures->setEntreprise($this);
        }

        return $this;
    }

    public function removeFactureId(Facture $factures): static
    {
        if ($this->factures->removeElement($factures)) {
            // set the owning side to null (unless already changed)
            if ($factures->getEntreprise() === $this) {
                $factures->setEntreprise(null);
            }
        }

        return $this;
    }
}