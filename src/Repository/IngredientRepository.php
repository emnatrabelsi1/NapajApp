<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function findAllOrderByCode(){
        return $this->createQueryBuilder('i')
        ->select('i,min(sp.price) as min_price,max(sp.price) as max_price')
            ->leftJoin('i.supplierPrices','sp')
            ->orderBy('i.code', 'ASC')
            ->groupBy('i')
            ->getQuery()
            ->getResult();
    }

    public function nbForCompanny($company){
        return $this->createQueryBuilder('i')
            ->select('Count(1)')
            ->where('i.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllOfCompany($company){
        return $this->createQueryBuilder('i')
            ->where('i.company = :company')
            ->setParameter('company', $company)
            ->select('i')
            ->orderBy('i.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
