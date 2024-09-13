<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LotReceptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LotReceptionRepository::class)]
#[ApiResource()]
class LotReception
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["reception:read", "lot:read"])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["reception:read", "lot:read"])]
    private ?Article $article = null;

    #[ORM\Column]
    #[Groups(["reception:read", "lot:read"])]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'lotReceptions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["lot:read"])]
    private ?Reception $reception = null;

    #[ORM\OneToOne(inversedBy: 'lotReception', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["reception:read"])]
    private ?Lot $lot = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getReception(): ?Reception
    {
        return $this->reception;
    }

    public function setReception(?Reception $reception): static
    {
        $this->reception = $reception;

        return $this;
    }

    public function getLot(): ?Lot
    {
        return $this->lot;
    }

    public function setLot(Lot $lot): static
    {
        $this->lot = $lot;

        return $this;
    }
}
