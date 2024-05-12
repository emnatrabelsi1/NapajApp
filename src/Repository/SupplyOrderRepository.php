<?php

namespace App\Repository;

use App\Entity\SupplyOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SupplyOrder>
 *
 * @method SupplyOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplyOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplyOrder[]    findAll()
 * @method SupplyOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplyOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplyOrder::class);
    }

    public function findAllPending(): array
    {
        return $this->createQueryBuilder('s')
        ->innerJoin('s.state', 'sos')
        ->andWhere('sos.code = \'pending\'')
        ->orderBy('s.created_at', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }

}
