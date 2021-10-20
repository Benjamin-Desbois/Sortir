<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use ArrayAccess;
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
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="participant_inscription")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participants_no_participant;

    /**
     * @ORM\ManyToOne(targetEntity=Sortie::class, inversedBy="sortie_inscription")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sorties_no_sortie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipantsNoParticipant(): ?Participant
    {
        return $this->participants_no_participant;
    }

    public function setParticipantsNoParticipant(?Participant $participants_no_participant): self
    {
        $this->participants_no_participant = $participants_no_participant;

        return $this;
    }

    public function getSortiesNoSortie(): ?Sortie
    {
        return $this->sorties_no_sortie;
    }

    public function setSortiesNoSortie(?Sortie $sorties_no_sortie): self
    {
        $this->sorties_no_sortie = $sorties_no_sortie;

        return $this;
    }
}
