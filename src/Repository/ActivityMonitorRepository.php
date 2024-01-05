<?php

namespace App\Repository;

use App\Entity\ActivityMonitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityMonitor>
 *
 * @method ActivityMonitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityMonitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityMonitor[]    findAll()
 * @method ActivityMonitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityMonitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityMonitor::class);
    }

//    /**
//     * @return ActivityMonitor[] Returns an array of ActivityMonitor objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ActivityMonitor
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
