<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReceptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReceptionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['reception:read']],
)]
class Reception
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["reception:read", "lot:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["reception:read", "lot:read"])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["reception:read", "lot:read"])]
    private ?Fournisseur $fournisseur = null;

    /**
     * @var Collection<int, LotReception>
     */
    #[ORM\OneToMany(targetEntity: LotReception::class, mappedBy: 'reception')]
    #[Groups(["reception:read"])]
    private Collection $lotReceptions;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["reception:read", "lot:read"])]
    private ?string $numero = null;

    public function __construct()
    {
        $this->lotReceptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * @return Collection<int, LotReception>
     */
    public function getLotReceptions(): Collection
    {
        return $this->lotReceptions;
    }

    public function addLotReception(LotReception $lotReception): static
    {
        if (!$this->lotReceptions->contains($lotReception)) {
            $this->lotReceptions->add($lotReception);
            $lotReception->setReception($this);
        }

        return $this;
    }

    public function removeLotReception(LotReception $lotReception): static
    {
        if ($this->lotReceptions->removeElement($lotReception)) {
            // set the owning side to null (unless already changed)
            if ($lotReception->getReception() === $this) {
                $lotReception->setReception(null);
            }
        }

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }
}
