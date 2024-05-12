<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByCriteria($criteria): array
    {
        $qb = $this->createQueryBuilder('p');
        
        if(isset($criteria['tags'])){
            $qb->innerJoin('p.tags', 't')
            ->andWhere("t.id in (:val)")
            ->setParameter('val', $criteria['tags']);
        }
        if(isset($criteria['out_of_stock']) && $criteria['out_of_stock'] == "true"){
            $qb->andWhere("p.stock < p.minimum_stock");
        }
        if(isset($criteria['company_id'])){
            $qb->andWhere("p.company = :company_id")
            ->setParameter('company_id', $criteria['company_id']);
        }

        return $qb->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

}
