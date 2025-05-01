<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    // Example custom query method
    public function findByRole(string $role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.role = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getResult();
    }

    public function findActiveUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.status = :status')
            ->setParameter('status', 'active')
            ->getQuery()
            ->getResult();
    }

    public function findRoleByAdresse(string $email): ?string
    {
        $result = $this->createQueryBuilder('u')
            ->select('u.role')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
        return $result ? $result['role'] : null;
    }

    public function chercherEmail(string $email): ?bool
    {
        $result = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getSingleScalarResult();
        return $result > 0;
    }
    public function ChercherUserSelonEmailAndPassword(string $email, string $mot_de_passe): ?Users
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if ($user && password_verify($mot_de_passe, $user->getPasswordHash())) {
            return $user;
        }
        
        return null;
    }

}
