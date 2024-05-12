<?php

namespace App\Repository;

use App\Entity\Order;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findAllWithInternal($archived){
        return $this->createQueryBuilder('o')
            ->innerJoin('o.customer','c')
            ->select('o')
            ->where('o.archived = :archived')
            ->setParameter('archived',$archived)
            ->orderBy('o.delivery_date ASC, o.event_date ASC, c.internal', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
