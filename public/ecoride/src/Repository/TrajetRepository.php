<?php

namespace App\Repository;

use App\Entity\Trajet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trajet>
 */
class TrajetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trajet::class);
    }

    /**
     * Recherche personnalisée
     */
   
    public function search(?string $depart, ?string $arrivee, ?string $date, ?string $heure)
    {
        $qb = $this->createQueryBuilder('t');

        if ($depart) {
            $qb->andWhere('t.depart LIKE :depart')
            ->setParameter('depart', "%$depart%");
        }

        if ($arrivee) {
            $qb->andWhere('t.arrivee LIKE :arrivee')
            ->setParameter('arrivee', "%$arrivee%");
        }

        if ($date) {
            $dateObj = new \DateTime($date);
            $qb->andWhere('t.date >= :dateStart')
            ->andWhere('t.date < :dateEnd')
            ->setParameter('dateStart', $dateObj->format('Y-m-d 00:00:00'))
            ->setParameter('dateEnd', $dateObj->format('Y-m-d 23:59:59'));
        }

        if ($heure) {
            $qb->andWhere('t.heure >= :heure')
            ->setParameter('heure', $heure);
        }

        return $qb->getQuery()->getResult();
    }

    public function countReservedSeats(Trajet $trajet): int
    {
        return (int) $this->createQueryBuilder('t')
            ->select('SUM(r.places)')
            ->leftJoin('t.reservations', 'r')
            ->andWhere('t = :trajet')
            ->setParameter('trajet', $trajet)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPlacesRestantes(): int
    {
        $totalReserve = 0;

        foreach ($this->reservations as $reservation) {
            $totalReserve += $reservation->getPlaces();
        }

        return $this->places - $totalReserve;
    }

}
