<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez saisir la Reference svp !")
     * @Assert\Length(
     *      min = 3,
     *      max = 8,
     *      minMessage = "La reference doit etre de longueur {{ limit }} chiffres au minimum",
     *      maxMessage = "La longueur de la reference ne doit pas depasser {{ limit }} chiffres"
     * )
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir le Nom svp !")
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez saisir la quantité svp !")
     * @Assert\Positive(message="Veuillez saisir une quantité positive svp !")
     */
    private $quantite;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir une Description svp !")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Veuillez saisir le Prix svp !")
     * @Assert\Positive(message="Veuillez saisir un Prix positif svp !")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="produit", cascade={"persist"})
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }
}
