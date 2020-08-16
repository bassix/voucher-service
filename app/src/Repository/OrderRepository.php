<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrderEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderEntity[]    findAll()
 * @method OrderEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderEntity::class);
    }

    public function findOrderAndCustomer($orderId, $customerId): ?string
    {
        return $this->createQueryBuilder('orders')
            ->select('*')
            ->andWhere('order_id = :order_id')
            ->setParameter('order_id', $orderId)
            ->andWhere('customer_id = :customer_id')
            ->setParameter('customer_id', $customerId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
