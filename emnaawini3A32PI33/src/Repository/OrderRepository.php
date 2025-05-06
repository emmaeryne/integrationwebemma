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
    public function findBySearch(?string $searchTerm, int $start = 0, int $length = 10): array
{
    $qb = $this->createQueryBuilder('o')
        ->leftJoin('o.user', 'u')
        ->setFirstResult($start)
        ->setMaxResults($length)
        ->orderBy('o.id', 'DESC');
    
    if ($searchTerm) {
        $qb->where('o.reference LIKE :searchTerm')
            ->orWhere('u.firstname LIKE :searchTerm')
            ->orWhere('u.lastname LIKE :searchTerm')
            ->orWhere('CONCAT(u.firstname, \' \', u.lastname) LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.addcslashes($searchTerm, '%_').'%');
    }
    
    return $qb->getQuery()->getResult();
}

public function countSearchResults(string $searchTerm): int
{
    $qb = $this->createQueryBuilder('o')
        ->select('COUNT(o.id)')
        ->leftJoin('o.user', 'u');
    
    $qb->where('o.reference LIKE :searchTerm')
        ->orWhere('u.firstname LIKE :searchTerm')
        ->orWhere('u.lastname LIKE :searchTerm')
        ->orWhere('CONCAT(u.firstname, \' \', u.lastname) LIKE :searchTerm')
        ->setParameter('searchTerm', '%'.addcslashes($searchTerm, '%_').'%');
    
    return (int) $qb->getQuery()->getSingleScalarResult();
}
    
    public function searchOrders(string $query): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.user', 'u')
            ->where('o.id = :id')
            ->orWhere('o.reference LIKE :query')
            ->orWhere('u.firstname LIKE :query')
            ->orWhere('u.lastname LIKE :query')
            ->orWhere('CONCAT(u.firstname, \' \', u.lastname) LIKE :query')
            ->setParameter('id', is_numeric($query) ? (int)$query : 0)
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }


    
}