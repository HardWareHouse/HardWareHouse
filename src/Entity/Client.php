<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'clientId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprise $entrepriseId = null;

    #[ORM\OneToMany(mappedBy: 'clientId', targetEntity: Devis::class)]
    private Collection $devisId;

    #[ORM\OneToMany(mappedBy: 'clientId', targetEntity: Facture::class)]
    private Collection $factureId;

    public function __construct()
    {
        $this->devisId = new ArrayCollection();
        $this->factureId = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

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
     * @return Collection<int, Devis>
     */
    public function getDevisId(): Collection
    {
        return $this->devisId;
    }

    public function addDevisId(Devis $devisId): static
    {
        if (!$this->devisId->contains($devisId)) {
            $this->devisId->add($devisId);
            $devisId->setClientId($this);
        }

        return $this;
    }

    public function removeDevisId(Devis $devisId): static
    {
        if ($this->devisId->removeElement($devisId)) {
            // set the owning side to null (unless already changed)
            if ($devisId->getClientId() === $this) {
                $devisId->setClientId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactureId(): Collection
    {
        return $this->factureId;
    }

    public function addFactureId(Facture $factureId): static
    {
        if (!$this->factureId->contains($factureId)) {
            $this->factureId->add($factureId);
            $factureId->setClientId($this);
        }

        return $this;
    }

    public function removeFactureId(Facture $factureId): static
    {
        if ($this->factureId->removeElement($factureId)) {
            // set the owning side to null (unless already changed)
            if ($factureId->getClientId() === $this) {
                $factureId->setClientId(null);
            }
        }

        return $this;
    }
}
