<?php

namespace App\Entity;

use App\Repository\TrajetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Reservation;

#[ORM\Entity(repositoryClass: TrajetRepository::class)]
class Trajet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $depart = null;

    #[ORM\Column(length: 255)]
    private ?string $arrivee = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heure = null;

    #[ORM\Column]
    private ?int $places = 0;

    #[ORM\Column]
    private ?float $prix = 0;

    #[ORM\ManyToOne(inversedBy: 'trajets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $chauffeur = null;

    #[ORM\OneToMany(mappedBy: 'trajet', targetEntity: Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    // =======================================================================
    // GETTERS / SETTERS
    // =======================================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): static
    {
        $this->depart = $depart;
        return $this;
    }

    public function getArrivee(): ?string
    {
        return $this->arrivee;
    }

    public function setArrivee(string $arrivee): static
    {
        $this->arrivee = $arrivee;
        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getHeure(): ?\DateTime
    {
        return $this->heure;
    }

    public function setHeure(\DateTime $heure): static
    {
        $this->heure = $heure;
        return $this;
    }

    public function getPlaces(): int
    {
        return $this->places ?? 0;
    }

    public function setPlaces(int $places): static
    {
        $this->places = max(0, $places);
        return $this;
    }

    public function getPrix(): float
    {
        return $this->prix ?? 0;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = max(0, $prix);
        return $this;
    }

    public function getChauffeur(): ?User
    {
        return $this->chauffeur;
    }

    public function setChauffeur(?User $chauffeur): static
    {
        $this->chauffeur = $chauffeur;
        return $this;
    }

    // =======================================================================
    // RELATIONS RESERVATIONS
    // =======================================================================

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setTrajet($this);
        }
        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getTrajet() === $this) {
                $reservation->setTrajet(null);
            }
        }
        return $this;
    }

    // =======================================================================
    // MÉTHODES MÉTIER
    // =======================================================================

    /**
     * Total places réservées
     */
    public function getTotalPlacesReservees(): int
    {
        $total = 0;

        foreach ($this->reservations as $reservation) {
            $total += $reservation->getPlaces();
        }

        return $total;
    }

    /**
     * Places restantes
     */
    public function getPlacesRestantes(): int
    {
        return max(0, $this->getPlaces() - $this->getTotalPlacesReservees());
    }

    /**
     * État du trajet (utile pour afficher des badges)
     * - complet
     * - urgent (moins de 2 places)
     * - disponible
     */
    public function getEtatTrajet(): string
    {
        $restantes = $this->getPlacesRestantes();

        if ($restantes <= 0) {
            return 'complet';
        }

        if ($restantes <= 2) {
            return 'urgent';
        }

        return 'disponible';
    }

    /**
     * Chiffre d’affaires généré par le trajet
     */
    public function getChiffreAffaires(): float
    {
        return $this->getTotalPlacesReservees() * $this->getPrix();
    }
}
