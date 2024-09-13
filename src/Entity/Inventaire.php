<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InventaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InventaireRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['inventaire:read']],
)]
class Inventaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["inventaire:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["inventaire:read"])]
    private ?string $reference = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["inventaire:read"])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(["inventaire:read"])]
    private ?string $depot = null;

    /**
     * @var Collection<int, LotInventaire>
     */
    #[ORM\OneToMany(targetEntity: LotInventaire::class, mappedBy: 'inventaire')]
    #[Groups(["inventaire:read"])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $lotInventaires;

    public function __construct()
    {
        $this->lotInventaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDepot(): ?string
    {
        return $this->depot;
    }

    public function setDepot(string $depot): static
    {
        $this->depot = $depot;

        return $this;
    }

    /**
     * @return Collection<int, LotInventaire>
     */
    public function getLotInventaires(): Collection
    {
        return $this->lotInventaires;
    }

    public function addLotInventaire(LotInventaire $lotInventaire): static
    {
        if (!$this->lotInventaires->contains($lotInventaire)) {
            $this->lotInventaires->add($lotInventaire);
            $lotInventaire->setInventaire($this);
        }

        return $this;
    }

    public function removeLotInventaire(LotInventaire $lotInventaire): static
    {
        if ($this->lotInventaires->removeElement($lotInventaire)) {
            // set the owning side to null (unless already changed)
            if ($lotInventaire->getInventaire() === $this) {
                $lotInventaire->setInventaire(null);
            }
        }

        return $this;
    }
}
