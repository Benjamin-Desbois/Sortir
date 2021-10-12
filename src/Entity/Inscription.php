<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 */
class Inscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_inscription;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="inscriptions_sorties")
     */
    private $sorties_no_sortie;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="inscriptions_participants")
     */
    private $participants_no_participant;

    public function __construct()
    {
        $this->sorties_no_sortie = new ArrayCollection();
        $this->participants_no_participant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesNoSortie(): Collection
    {
        return $this->sorties_no_sortie;
    }

    public function addSortiesNoSortie(Sortie $sortiesNoSortie): self
    {
        if (!$this->sorties_no_sortie->contains($sortiesNoSortie)) {
            $this->sorties_no_sortie[] = $sortiesNoSortie;
            $sortiesNoSortie->setInscriptionsSorties($this);
        }

        return $this;
    }

    public function removeSortiesNoSortie(Sortie $sortiesNoSortie): self
    {
        if ($this->sorties_no_sortie->removeElement($sortiesNoSortie)) {
            // set the owning side to null (unless already changed)
            if ($sortiesNoSortie->getInscriptionsSorties() === $this) {
                $sortiesNoSortie->setInscriptionsSorties(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipantsNoParticipant(): Collection
    {
        return $this->participants_no_participant;
    }

    public function addParticipantsNoParticipant(Participant $participantsNoParticipant): self
    {
        if (!$this->participants_no_participant->contains($participantsNoParticipant)) {
            $this->participants_no_participant[] = $participantsNoParticipant;
            $participantsNoParticipant->setInscriptionsParticipants($this);
        }

        return $this;
    }

    public function removeParticipantsNoParticipant(Participant $participantsNoParticipant): self
    {
        if ($this->participants_no_participant->removeElement($participantsNoParticipant)) {
            // set the owning side to null (unless already changed)
            if ($participantsNoParticipant->getInscriptionsParticipants() === $this) {
                $participantsNoParticipant->setInscriptionsParticipants(null);
            }
        }

        return $this;
    }
}
