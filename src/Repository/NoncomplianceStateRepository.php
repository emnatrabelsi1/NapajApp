<?php

namespace App\Repository;

use App\Entity\NoncomplianceState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NoncomplianceState>
 *
 * @method NoncomplianceState|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoncomplianceState|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoncomplianceState[]    findAll()
 * @method NoncomplianceState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoncomplianceStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoncomplianceState::class);
    }

//    /**
//     * @return NoncomplianceState[] Returns an array of NoncomplianceState objects
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

//    public function findOneBySomeField($value): ?NoncomplianceState
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
