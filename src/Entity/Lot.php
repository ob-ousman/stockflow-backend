<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\LotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LotRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['lot:read']],
)]
#[ApiFilter(SearchFilter::class, properties: ['numero' => 'exact'])]
class Lot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["inventaire:read", "reception:read", "lot:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["inventaire:read", "reception:read", "lot:read"])]
    private ?string $numero = null;

    #[ORM\Column(length: 255)]
    #[Groups(["inventaire:read", "reception:read", "lot:read"])]
    private ?string $qrcode = null;

    #[ORM\Column(length: 255)]
    #[Groups(["inventaire:read", "reception:read", "lot:read"])]
    private ?string $lotFournisseur = null;

    #[ORM\OneToOne(mappedBy: 'lot', cascade: ['persist', 'remove'])]
    #[Groups(["lot:read"])]
    private ?LotReception $lotReception = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["inventaire:read", "reception:read", "lot:read"])]
    private ?\DateTimeInterface $peremption = null;

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

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(string $qrcode): static
    {
        $this->qrcode = $qrcode;

        return $this;
    }

    public function getLotFournisseur(): ?string
    {
        return $this->lotFournisseur;
    }

    public function setLotFournisseur(string $lotFournisseur): static
    {
        $this->lotFournisseur = $lotFournisseur;

        return $this;
    }

    public function getLotReception(): ?LotReception
    {
        return $this->lotReception;
    }

    public function setLotReception(LotReception $lotReception): static
    {
        // set the owning side of the relation if necessary
        if ($lotReception->getLot() !== $this) {
            $lotReception->setLot($this);
        }

        $this->lotReception = $lotReception;

        return $this;
    }

    public function getPeremption(): ?\DateTimeInterface
    {
        return $this->peremption;
    }

    public function setPeremption(?\DateTimeInterface $peremption): static
    {
        $this->peremption = $peremption;

        return $this;
    }
}
