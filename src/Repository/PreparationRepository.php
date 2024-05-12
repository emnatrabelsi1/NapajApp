<?php

namespace App\Repository;

use App\Entity\Preparation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Preparation>
 *
 * @method Preparation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Preparation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Preparation[]    findAll()
 * @method Preparation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreparationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Preparation::class);
    }

    public function findAllOfCompany($company){
        return $this->createQueryBuilder('p')
            ->where('p.company = :company')
            ->setParameter('company', $company)
            ->select('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
