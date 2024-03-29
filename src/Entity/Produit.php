<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: 'nom', message: 'Un produit avec ce nom existe déjà.')]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit ne peut pas être vide.")]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(value: 0, message: "Le prix ne peut pas être négatif.")]
    private ?float $prix = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(value: 0, message: "Le stock ne peut pas être négatif.")]
    private ?int $stock = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'produitId')]
    #[ORM\JoinColumn(name: "entreprise", referencedColumnName: "id")]
    private ?Entreprise $entrepriseId = null;

    #[ORM\ManyToOne(inversedBy: 'produitId')]
    #[ORM\JoinColumn(name: "categorie", referencedColumnName: "id")]
    private ?Categorie $categorieId = null;

    #[ORM\Column(nullable: true)]
    private ?int $tva = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: DetailDevis::class, orphanRemoval: true)]
    private Collection $detailDevis;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: DetailFacture::class)]
    private Collection $detailFactures;

    public function __construct()
    {
        $this->detailDevis = new ArrayCollection();
        $this->CreatedAt = new \DateTimeImmutable('now');
        $this->detailFactures = new ArrayCollection();
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

    public function getTva(): ?int
    {
        return $this->tva;
    }

    public function setTva(?int $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * @return Collection<int, DetailDevis>
     */
    public function getDetailDevis(): Collection
    {
        return $this->detailDevis;
    }

    public function addDetailDevi(DetailDevis $detailDevi): static
    {
        if (!$this->detailDevis->contains($detailDevi)) {
            $this->detailDevis->add($detailDevi);
            $detailDevi->setProduit($this);
        }

        return $this;
    }

    public function removeDetailDevi(DetailDevis $detailDevi): static
    {
        if ($this->detailDevis->removeElement($detailDevi)) {
            // set the owning side to null (unless already changed)
            if ($detailDevi->getProduit() === $this) {
                $detailDevi->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DetailFacture>
     */
    public function getDetailFactures(): Collection
    {
        return $this->detailFactures;
    }

    public function addDetailFacture(DetailFacture $detailFacture): static
    {
        if (!$this->detailFactures->contains($detailFacture)) {
            $this->detailFactures->add($detailFacture);
            $detailFacture->setProduit($this);
        }

        return $this;
    }

    public function removeDetailFacture(DetailFacture $detailFacture): static
    {
        if ($this->detailFactures->removeElement($detailFacture)) {
            // set the owning side to null (unless already changed)
            if ($detailFacture->getProduit() === $this) {
                $detailFacture->setProduit(null);
            }
        }

        return $this;
    }
}