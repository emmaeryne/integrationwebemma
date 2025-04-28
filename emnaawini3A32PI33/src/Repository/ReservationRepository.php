<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['dateReservation' => 'DESC']);
    }

    /**
     * Supprime toutes les réservations dont la dateFin est dépassée.
     *
     * @return int Le nombre de réservations supprimées.
     */
    public function deleteExpiredReservations(): int
    {
        $currentDate = new \DateTime();

        $expiredReservations = $this->createQueryBuilder('r')
            ->where('r.dateFin <= :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getResult();

        $count = count($expiredReservations);

        if ($count > 0) {
            foreach ($expiredReservations as $reservation) {
                $this->getEntityManager()->remove($reservation);
            }
            $this->getEntityManager()->flush();
        }

        return $count;
    }

    /**
     * Met à jour le statut des réservations dont la dateFin est dépassée à "terminé".
     *
     * @return int Le nombre de réservations mises à jour.
     */
    public function updateExpiredReservationsStatus(): int
    {
        $currentDate = new \DateTime();

        $expiredReservations = $this->createQueryBuilder('r')
            ->where('r.dateFin <= :currentDate')
            ->andWhere('r.statut != :status') // Ne met à jour que si le statut n'est pas déjà "terminé"
            ->setParameter('currentDate', $currentDate)
            ->setParameter('status', 'terminé')
            ->getQuery()
            ->getResult();

        $count = count($expiredReservations);

        if ($count > 0) {
            foreach ($expiredReservations as $reservation) {
                $reservation->setStatut('terminé');
                $this->getEntityManager()->persist($reservation);
            }
            $this->getEntityManager()->flush();
        }

        return $count;
    }
}