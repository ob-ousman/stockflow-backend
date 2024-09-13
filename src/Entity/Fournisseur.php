<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['fournisseur:read']],
)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["fournisseur:read", "reception:read", "lot:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["fournisseur:read", "reception:read", "lot:read"])]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(["fournisseur:read", "reception:read", "lot:read"])]
    private ?string $intitule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }
}
