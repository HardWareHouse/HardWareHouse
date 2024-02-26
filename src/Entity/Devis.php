<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'devisId')]
    #[ORM\JoinColumn(name: "entreprise", referencedColumnName: "id")]
    private ?Entreprise $entrepriseId = null;

    #[ORM\ManyToOne(inversedBy: 'devisId')]
    #[ORM\JoinColumn(name: "client", referencedColumnName: "id")]
    private ?Client $clientId = null;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: DetailDevis::class , cascade: ['persist','remove'])]
    private Collection $detailDevis;

    public function __construct()
    {
        $this->CreatedAt = new \DateTimeImmutable('now');
        $this->detailDevis = new ArrayCollection();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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
            $detailDevi->setDevis($this);
        }

        return $this;
    }

    public function removeDetailDevi(DetailDevis $detailDevi): static
    {
        if ($this->detailDevis->removeElement($detailDevi)) {
            // set the owning side to null (unless already changed)
            if ($detailDevi->getDevis() === $this) {
                $detailDevi->setDevis(null);
            }
        }

        return $this;
    }


}