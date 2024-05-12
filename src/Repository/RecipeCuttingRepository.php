<?php

namespace App\Repository;

use App\Entity\RecipeCutting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecipeCutting>
 *
 * @method RecipeCutting|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeCutting|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeCutting[]    findAll()
 * @method RecipeCutting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeCuttingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeCutting::class);
    }

//    /**
//     * @return RecipeCutting[] Returns an array of RecipeCutting objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RecipeCutting
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
