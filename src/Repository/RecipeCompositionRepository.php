<?php

namespace App\Repository;

use App\Entity\RecipeComposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecipeComposition>
 *
 * @method RecipeComposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeComposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeComposition[]    findAll()
 * @method RecipeComposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeCompositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeComposition::class);
    }
}
