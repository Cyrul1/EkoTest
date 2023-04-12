<?php

namespace App\Entity;

use App\Repository\MagazynyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagazynyRepository::class)]
class Magazyny
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nazwa = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'UserMagazyn')]
    private Collection $Pracownik_magazynu;

    #[ORM\OneToMany(mappedBy: 'magazyny', targetEntity: Artykul::class)]
    private Collection $Artykuly;

    public function __construct()
    {
        $this->Pracownik_magazynu = new ArrayCollection();
        $this->Artykuly = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNazwa(): ?string
    {
        return $this->Nazwa;
    }

    public function setNazwa(string $Nazwa): self
    {
        $this->Nazwa = $Nazwa;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPracownikMagazynu(): Collection
    {
        return $this->Pracownik_magazynu;
    }

    public function addPracownikMagazynu(User $pracownikMagazynu): self
    {
        if (!$this->Pracownik_magazynu->contains($pracownikMagazynu)) {
            $this->Pracownik_magazynu->add($pracownikMagazynu);
        }

        return $this;
    }

    public function removePracownikMagazynu(User $pracownikMagazynu): self
    {
        $this->Pracownik_magazynu->removeElement($pracownikMagazynu);

        return $this;
    }

    /**
     * @return Collection<int, Artykul>
     */
    public function getArtykuly(): Collection
    {
        return $this->Artykuly;
    }

    public function addArtykuly(Artykul $artykuly): self
    {
        if (!$this->Artykuly->contains($artykuly)) {
            $this->Artykuly->add($artykuly);
            $artykuly->setMagazyny($this);
        }

        return $this;
    }

    public function removeArtykuly(Artykul $artykuly): self
    {
        if ($this->Artykuly->removeElement($artykuly)) {
            // set the owning side to null (unless already changed)
            if ($artykuly->getMagazyny() === $this) {
                $artykuly->setMagazyny(null);
            }
        }

        return $this;
    }
}
