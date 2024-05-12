<?php

namespace App\Repository;

use App\Entity\SupplyOrderState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SupplyOrderState>
 *
 * @method SupplyOrderState|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplyOrderState|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplyOrderState[]    findAll()
 * @method SupplyOrderState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyOrderStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplyOrderState::class);
    }

//    /**
//     * @return SupplyOrderState[] Returns an array of SupplyOrderState objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SupplyOrderState
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
