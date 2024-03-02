<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
#[UniqueEntity(fields: 'nom', message: 'Une entreprise avec ce nom existe déjà.')]
#[UniqueEntity(fields: 'email', message: 'Un email d\'entreprise avec cette adresse existe déjà.')]
#[UniqueEntity(fields: 'telephone', message: 'Un numéro de téléphone d\'entreprise avec ce numéro existe déjà.')]
#[UniqueEntity(fields: 'siren', message: 'Ce numéro SIREN/SIRET est déjà utilisé.')]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(max: 250)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_uuid", referencedColumnName: "uuid")]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'entrepriseId', targetEntity: Client::class)]
    #[ORM\JoinColumn(name: "client", referencedColumnName: "id")]
    private Collection $clientId;

    #[ORM\OneToMany(mappedBy: 'entrepriseId', targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: "produit", referencedColumnName: "id")]
    private Collection $produitId;

    #[ORM\OneToMany(mappedBy: 'entrepriseId', targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: "categorie", referencedColumnName: "id")]
    private Collection $categorieId;

    #[ORM\OneToMany(mappedBy: 'entrepriseId', targetEntity: Devis::class)]
    #[ORM\JoinColumn(name: "devis", referencedColumnName: "id")]
    private Collection $devisId;

    #[ORM\OneToMany(mappedBy: 'entrepriseId', targetEntity: Facture::class)]
    #[ORM\JoinColumn(name: "facture", referencedColumnName: "id")]
    private Collection $factureId;

    #[ORM\OneToMany(mappedBy: 'entrepriseId', targetEntity: Paiement::class)]
    #[ORM\JoinColumn(name: "paiement", referencedColumnName: "id")]
    private Collection $paiementId;

    #[ORM\OneToOne(inversedBy: 'entreprise', cascade: ['persist', 'remove'])]
    private ?Media $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 50)]
    #[Assert\Regex(pattern: '/^[a-zA-Z]+(?:[-\s][a-zA-Z]+)*$/', message: 'La ville ne doit contenir que des lettres et peut inclure des tirets.')]
    private ?string $ville = null;

    #[ORM\Column(nullable: true, length: 5)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{5}$/', message: 'Le code postal doit être composé de 5 chiffres.')]
    private ?int $codePostal = null;

    #[ORM\Column(length: 14, nullable: true)]
    #[Assert\Regex(pattern: '/^(?:[1-2]\d{8}|[1-2]\d{13})$/', message: 'Le SIREN doit contenir 9 chiffres et le SIRET doit contenir 14 chiffres, tous deux commençant par 1 ou 2.')]
    private ?string $siren = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message: 'L\'URL du site web n\'est pas valide. Veuillez renseigner une URL valide de la forme http://www.example.com')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'L\'URL du site web ne doit pas dépasser {{ limit }} caractères.'
    )]
    private ?string $siteWeb = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(message: 'L\'email n\'est pas valide.')]
    private ?string $email = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Regex(pattern: '/^(01|02|03|04|05|09)\d{8}$/', message: 'Le téléphone doit commencer par 01, 02, 03, 04, 05 ou 09 et contenir 10 chiffres.')]
    private ?string $telephone = null;

    public function __construct()
    {
        $this->clientId = new ArrayCollection();
        $this->produitId = new ArrayCollection();
        $this->categorieId = new ArrayCollection();
        $this->devisId = new ArrayCollection();
        $this->factureId = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->CreatedAt = new \DateTimeImmutable();
        $this->paiementId = new ArrayCollection();
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

        /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            // Set the inverse side of the relationship
            $user->setEntreprise($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // Set the inverse side of the relationship to null
            $user->setEntreprise(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clientId;
    }

    public function addClient(Client $clientId): static
    {
        if (!$this->clientId->contains($clientId)) {
            $this->clientId->add($clientId);
            $clientId->setEntrepriseId($this);
        }

        return $this;
    }

    public function removeClient(Client $clientId): static
    {
        if ($this->clientId->removeElement($clientId)) {
            // set the owning side to null (unless already changed)
            if ($clientId->getEntrepriseId() === $this) {
                $clientId->setEntrepriseId(null);
            }
        }

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
            $produitId->setEntrepriseId($this);
        }

        return $this;
    }

    public function removeProduitId(Produit $produitId): static
    {
        if ($this->produitId->removeElement($produitId)) {
            // set the owning side to null (unless already changed)
            if ($produitId->getEntrepriseId() === $this) {
                $produitId->setEntrepriseId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategorieId(): Collection
    {
        return $this->categorieId;
    }

    public function addCategorieId(Categorie $categorieId): static
    {
        if (!$this->categorieId->contains($categorieId)) {
            $this->categorieId->add($categorieId);
            $categorieId->setEntrepriseId($this);
        }

        return $this;
    }

    public function removeCategorieId(Categorie $categorieId): static
    {
        if ($this->categorieId->removeElement($categorieId)) {
            // set the owning side to null (unless already changed)
            if ($categorieId->getEntrepriseId() === $this) {
                $categorieId->setEntrepriseId(null);
            }
        }

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
            $devisId->setEntrepriseId($this);
        }

        return $this;
    }

    public function removeDevisId(Devis $devisId): static
    {
        if ($this->devisId->removeElement($devisId)) {
            // set the owning side to null (unless already changed)
            if ($devisId->getEntrepriseId() === $this) {
                $devisId->setEntrepriseId(null);
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
            $factureId->setEntrepriseId($this);
        }

        return $this;
    }

    public function removeFactureId(Facture $factureId): static
    {
        if ($this->factureId->removeElement($factureId)) {
            // set the owning side to null (unless already changed)
            if ($factureId->getEntrepriseId() === $this) {
                $factureId->setEntrepriseId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiementId(): Collection
    {
        return $this->paiementId;
    }

    public function addPaiementId(Paiement $paiementId): static
    {
        if (!$this->paiementId->contains($paiementId)) {
            $this->paiementId->add($paiementId);
            $paiementId->setEntrepriseId($this);
        }

        return $this;
    }

    public function removePaiementID(Paiement $paiementId): static
    {
        if ($this->paiementId->removeElement($paiementId)) {
            // set the owning side to null (unless already changed)
            if ($paiementId->getEntreprise() === $this) {
                $paiementId->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getLogo(): ?Media
    {
        return $this->logo;
    }

    public function setLogo(?Media $logo): static
    {
        $this->logo = $logo;

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

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(?int $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteWeb(?string $siteWeb): static
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

}