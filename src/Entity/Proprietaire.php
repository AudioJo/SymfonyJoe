<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProprietaireRepository::class)
 */
class Proprietaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=Chaton::class, inversedBy="proprietaires")
     */
    private $Chaton;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    public function __construct()
    {
        $this->Chaton = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Chaton>
     */
    public function getChaton(): Collection
    {
        return $this->Chaton;
    }

    public function addChaton(Chaton $chaton): self
    {
        if (!$this->Chaton->contains($chaton)) {
            $this->Chaton[] = $chaton;
        }

        return $this;
    }

    public function removeChaton(Chaton $chaton): self
    {
        $this->Chaton->removeElement($chaton);

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
}
