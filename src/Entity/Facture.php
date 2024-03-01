<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFacturation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePaiementDue = null;

    #[ORM\Column(length: 255)]
    private ?string $statutPaiement = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'factureId')]
    #[ORM\JoinColumn(name: "entreprise", referencedColumnName: "id")]
    private ?Entreprise $entrepriseId = null;

    #[ORM\ManyToOne(inversedBy: 'factureId')]
    #[ORM\JoinColumn(name: "client", referencedColumnName: "id")]
    private ?Client $clientId = null;

    #[ORM\OneToMany(mappedBy: 'factureId', targetEntity: Paiement::class)]
    #[ORM\JoinColumn(name: "paiement", referencedColumnName: "id")]
    private Collection $paiementId;

    #[ORM\OneToMany(mappedBy: 'facture', targetEntity: DetailFacture::class, orphanRemoval: true, cascade: ['persist','remove'])]
    private Collection $detailFacture;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $devi = null;

    public function __construct()
    {
        $this->paiementId = new ArrayCollection();
        $this->detailFacture = new ArrayCollection();
        $this->CreatedAt = new \DateTimeImmutable('now');
        $this->dateFacturation = new \DateTime('now');
        $this->datePaiementDue = new \DateTime('now');
        $this->statutPaiement = "Impayée";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDateFacturation(): ?\DateTimeInterface
    {
        return $this->dateFacturation;
    }

    public function setDateFacturation(\DateTimeInterface $dateFacturation): static
    {
        $this->dateFacturation = $dateFacturation;

        return $this;
    }

    public function getDatePaiementDue(): ?\DateTimeInterface
    {
        return $this->datePaiementDue;
    }

    public function setDatePaiementDue(\DateTimeInterface $datePaiementDue): static
    {
        $this->datePaiementDue = $datePaiementDue;

        return $this;
    }

    public function getStatutPaiement(): ?string
    {
        return $this->statutPaiement;
    }

    public function setStatutPaiement(string $statutPaiement): static
    {
        $this->statutPaiement = $statutPaiement;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

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

    public function getClientId(): ?Client
    {
        return $this->clientId;
    }

    public function setClientId(?Client $clientId): static
    {
        $this->clientId = $clientId;

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
            $paiementId->setFactureId($this);
        }

        return $this;
    }

    public function removePaiementId(Paiement $paiementId): static
    {
        if ($this->paiementId->removeElement($paiementId)) {
            // set the owning side to null (unless already changed)
            if ($paiementId->getFactureId() === $this) {
                $paiementId->setFactureId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DetailFacture>
     */
    public function getDetailFacture(): Collection
    {
        return $this->detailFacture;
    }

    public function addDetailFacture(DetailFacture $detailFacture): static
    {
        if (!$this->detailFacture->contains($detailFacture)) {
            $this->detailFacture->add($detailFacture);
            $detailFacture->setFacture($this);
        }

        return $this;
    }

    public function removeDetailFacture(DetailFacture $detailFacture): static
    {
        if ($this->detailFacture->removeElement($detailFacture)) {
            // set the owning side to null (unless already changed)
            if ($detailFacture->getFacture() === $this) {
                $detailFacture->setFacture(null);
            }
        }

        return $this;
    }

    public function getDevi(): ?Devis
    {
        return $this->devi;
    }

    public function setDevi(?Devis $devi): static
    {
        $this->devi = $devi;

        return $this;
    }
}