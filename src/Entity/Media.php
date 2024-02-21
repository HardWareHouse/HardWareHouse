<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[Vich\Uploadable]
class Media implements \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'entreprise_logo', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'logo', cascade: ['persist', 'remove'])]
    private ?Entreprise $entreprise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): Media
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }        

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): Media
    {
        $this->imageName = $imageName;
        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): Media
    {
        $this->imageSize = $imageSize;
        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        // unset the owning side of the relation if necessary
        if ($entreprise === null && $this->entreprise !== null) {
            $this->entreprise->setLogo(null);
        }

        // set the owning side of the relation if necessary
        if ($entreprise !== null && $entreprise->getLogo() !== $this) {
            $entreprise->setLogo($this);
        }

        $this->entreprise = $entreprise;

        return $this;
    }

    // Permet d'eviter l'erreur de serialization de File qui n'est pas allowed
    public function serialize() 
    {
        return serialize([
            $this->id,
            $this->imageName,
            $this->imageSize,
            $this->updatedAt,
            $this->entreprise,
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->imageName,
            $this->imageSize,
            $this->updatedAt,
            $this->entreprise,
        ) = unserialize($serialized);
    }





}
