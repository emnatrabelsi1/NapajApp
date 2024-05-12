<?php

namespace App\Repository;

use App\Entity\PreparationIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PreparationIngredient>
 *
 * @method PreparationIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreparationIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreparationIngredient[]    findAll()
 * @method PreparationIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreparationIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreparationIngredient::class);
    }
}
