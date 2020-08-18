<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\VoucherEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoucherEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoucherEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoucherEntity[]    findAll()
 * @method VoucherEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoucherEntity::class);
    }
}
