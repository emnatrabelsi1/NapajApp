<?php

namespace App\Repository;

use App\Entity\NoncomplianceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NoncomplianceType>
 *
 * @method NoncomplianceType|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoncomplianceType|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoncomplianceType[]    findAll()
 * @method NoncomplianceType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoncomplianceTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoncomplianceType::class);
    }

}
