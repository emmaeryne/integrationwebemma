<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function add(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSuccessOrders($user)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.isPaid = 1')
            ->andWhere('o.user = :user')
            ->setParameter('user', $user)
            ->orderBy('o.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getDailyAndWeeklyOrderStats(int $days = 30): array
    {
        $date = new \DateTime("-$days days");
        
        $conn = $this->getEntityManager()->getConnection();
        
        // Requête pour les statistiques quotidiennes
        $dailySql = '
            SELECT 
                DATE(o.created_at) as day, 
                COUNT(o.id) as count
            FROM `order` o
            WHERE o.created_at >= :date
            GROUP BY day
            ORDER BY day ASC
        ';
        
        // Requête pour les statistiques hebdomadaires
        $weeklySql = '
            SELECT 
                YEARWEEK(o.created_at) as week, 
                COUNT(o.id) as count
            FROM `order` o
            WHERE o.created_at >= :date
            GROUP BY week
            ORDER BY week ASC
        ';
        
        $stmt = $conn->prepare($dailySql);
        $dailyResult = $stmt->executeQuery(['date' => $date->format('Y-m-d H:i:s')]);
        $dailyStats = $dailyResult->fetchAllAssociative();
        
        $stmt = $conn->prepare($weeklySql);
        $weeklyResult = $stmt->executeQuery(['date' => $date->format('Y-m-d H:i:s')]);
        $weeklyStats = $weeklyResult->fetchAllAssociative();
        
        // Formatage des données
        $formattedDaily = [];
        foreach ($dailyStats as $stat) {
            $formattedDaily[$stat['day']] = (int)$stat['count'];
        }
        
        $formattedWeekly = [];
        foreach ($weeklyStats as $stat) {
            $formattedWeekly['Semaine ' . $stat['week']] = (int)$stat['count'];
        }
        
        return [
            'daily' => $formattedDaily,
            'weekly' => $formattedWeekly
        ];
    }
    
    public function findByFilters(array $filters, string $sortBy = 'id', string $sortOrder = 'DESC', int $offset = 0, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('o')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy("o.{$sortBy}", $sortOrder);

        // Filtre par statut
        if (!empty($filters['status'])) {
            $qb->andWhere('o.isPaid = :isPaid')
               ->setParameter('isPaid', $filters['status'] === 'paid' ? 1 : 0);
        }

        // Filtre par plage de dates
        if (!empty($filters['dateFrom'])) {
            $qb->andWhere('o.createdAt >= :dateFrom')
               ->setParameter('dateFrom', new \DateTime($filters['dateFrom']));
        }
        if (!empty($filters['dateTo'])) {
            $qb->andWhere('o.createdAt <= :dateTo')
               ->setParameter('dateTo', (new \DateTime($filters['dateTo']))->setTime(23, 59, 59));
        }

        // Filtre par montant
        if (!empty($filters['amountMin'])) {
            $qb->andWhere('o.total >= :amountMin')
               ->setParameter('amountMin', $filters['amountMin'] * 100);
        }
        if (!empty($filters['amountMax'])) {
            $qb->andWhere('o.total <= :amountMax')
               ->setParameter('amountMax', $filters['amountMax'] * 100);
        }

        return $qb->getQuery()->getResult();
    }

    public function countByFilters(array $filters): int
    {
        $qb = $this->createQueryBuilder('o')
            ->select('COUNT(o.id)');

        // Filtre par statut
        if (!empty($filters['status'])) {
            $qb->andWhere('o.isPaid = :isPaid')
               ->setParameter('isPaid', $filters['status'] === 'paid' ? 1 : 0);
        }

        // Filtre par plage de dates
        if (!empty($filters['dateFrom'])) {
            $qb->andWhere('o.createdAt >= :dateFrom')
               ->setParameter('dateFrom', new \DateTime($filters['dateFrom']));
        }
        if (!empty($filters['dateTo'])) {
            $qb->andWhere('o.createdAt <= :dateTo')
               ->setParameter('dateTo', (new \DateTime($filters['dateTo']))->setTime(23, 59, 59));
        }

        // Filtre par montant
        if (!empty($filters['amountMin'])) {
            $qb->andWhere('o.total >= :amountMin')
               ->setParameter('amountMin', $filters['amountMin'] * 100);
        }
        if (!empty($filters['amountMax'])) {
            $qb->andWhere('o.total <= :amountMax')
               ->setParameter('amountMax', $filters['amountMax'] * 100);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}