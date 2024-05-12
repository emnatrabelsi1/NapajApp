<?php

namespace App\Repository;

use App\Entity\Noncompliance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Noncompliance>
 *
 * @method Noncompliance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Noncompliance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Noncompliance[]    findAll()
 * @method Noncompliance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoncomplianceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Noncompliance::class);
    }

//    /**
//     * @return Noncompliance[] Returns an array of Noncompliance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Noncompliance
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
