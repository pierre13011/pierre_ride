<?php

namespace App\Entity;

use App\Entity\Trajet;
use App\Entity\Reservation;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
#[UniqueEntity(fields: ['pseudo'], message: 'Ce pseudo est déjà pris.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:255)]
    private ?string $nom = null;

    #[ORM\Column(length:255)]
    private ?string $prenom = null;

    #[ORM\Column(length:255, unique:true)]
    private ?string $pseudo = null;

    #[ORM\Column(length:255, unique:true)]
    private ?string $email = null;

    #[ORM\Column(length:255)]
    private ?string $password = null;

    /**
     * Rôles stockés en JSON
     */
    #[ORM\Column(type:'json')]
    private array $roles = []; // vide -> ROLE_USER sera ajouté automatiquement

    #[ORM\Column(type:'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(length:255, nullable:true)]
    private ?string $verificationToken = null;

    #[ORM\OneToMany(mappedBy: 'chauffeur', targetEntity: Trajet::class)]
    private Collection $trajets;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reservation::class)]
    private Collection $reservations;

    public function __construct()
    {
        $this->trajets = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->roles = ['ROLE_USER']; // MINIMUM PAR DÉFAUT
    }


    // NOM
public function getNom(): ?string
{
    return $this->nom;
}

public function setNom(string $nom): static
{
    $this->nom = $nom;
    return $this;
}

// PRENOM
public function getPrenom(): ?string
{
    return $this->prenom;
}

public function setPrenom(string $prenom): static
{
    $this->prenom = $prenom;
    return $this;
}

// PSEUDO
public function getPseudo(): ?string
{
    return $this->pseudo;
}

public function setPseudo(string $pseudo): static
{
    $this->pseudo = $pseudo;
    return $this;
}

// EMAIL
public function getEmail(): ?string
{
    return $this->email;
}

public function setEmail(string $email): static
{
    $this->email = $email;
    return $this;
}

    
    // =========================
    // SECURITY
    // =========================

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * ROLE_USER est toujours présent
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        // ROLE_USER ne doit jamais être retiré
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        $this->roles = array_unique($roles);
        return $this;
    }

    /**
     * Ajoute proprement un rôle
     */
    public function addRole(string $role): static
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    // Chauffeur ?
    public function isChauffeur(): bool
    {
        return in_array('ROLE_CHAUFFEUR', $this->roles);
    }

    // Auth
    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }
    public function eraseCredentials(): void {}

    // Activation
    public function isVerified(): bool { return $this->isVerified; }
    public function setIsVerified(bool $verified): static { $this->isVerified = $verified; return $this; }
    public function getVerificationToken(): ?string { return $this->verificationToken; }
    public function setVerificationToken(?string $token): static { $this->verificationToken = $token; return $this; }

    // Relations
    public function getTrajets(): Collection { return $this->trajets; }
    public function addTrajet(Trajet $trajet): static { if (!$this->trajets->contains($trajet)) { $this->trajets->add($trajet); $trajet->setChauffeur($this);} return $this; }
    public function removeTrajet(Trajet $trajet): static { if ($this->trajets->removeElement($trajet) && $trajet->getChauffeur() === $this) { $trajet->setChauffeur(null);} return $this; }
    public function getReservations(): Collection { return $this->reservations; }
    public function addReservation(Reservation $reservation): static { if (!$this->reservations->contains($reservation)) { $this->reservations->add($reservation); $reservation->setUser($this);} return $this; }
    public function removeReservation(Reservation $reservation): static { if ($this->reservations->removeElement($reservation) && $reservation->getUser() === $this) { $reservation->setUser(null);} return $this; }
}
