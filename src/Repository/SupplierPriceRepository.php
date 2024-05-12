<?php

namespace App\Repository;

use App\Entity\SupplierPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SupplierPrice>
 *
 * @method SupplierPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplierPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplierPrice[]    findAll()
 * @method SupplierPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplierPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplierPrice::class);
    }

    public function findAllOrderByCode(){
        return $this->createQueryBuilder('sp')
            ->leftJoin('sp.ingredient','i')
            ->orderBy('i.code', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
