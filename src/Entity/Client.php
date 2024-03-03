<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[UniqueEntity(fields: 'telephone', message: 'Ce numéro de téléphone est déjà utilisé par un autre client.')]
#[UniqueEntity(fields: 'email', message: 'Cet email avec cette adresse est déjà utilisé par un autre client.')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(message: 'L\'adresse email n\'est pas valide.')]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'clientId')]
    #[ORM\JoinColumn(name: "entreprise", referencedColumnName: "id", nullable: false)]
    private ?Entreprise $entrepriseId = null;

    #[ORM\OneToMany(mappedBy: 'clientId', targetEntity: Devis::class)]
    #[ORM\JoinColumn(name: "devis", referencedColumnName: "id")]
    private Collection $devisId;

    #[ORM\OneToMany(mappedBy: 'clientId', targetEntity: Facture::class)]
    #[ORM\JoinColumn(name: "facture", referencedColumnName: "id")]
    private Collection $factureId;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(pattern: '/^\d{5}$/', message: 'Le code postal doit être composé de 5 chiffres.')]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(pattern: '/^[a-zA-Z]+(?:[-\s][a-zA-Z]+)*$/', message: 'La ville ne doit contenir que des lettres et peut inclure des tirets.')]
    private ?string $ville = null;

    public function __construct()
    {
        $this->devisId = new ArrayCollection();
        $this->factureId = new ArrayCollection();
        $this->CreatedAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->nom;
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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }
}