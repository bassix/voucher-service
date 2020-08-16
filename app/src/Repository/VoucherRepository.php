<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\VoucherEntity;
use Doctrine\ORM\EntityRepository;

/**
 * @method VoucherEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoucherEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoucherEntity[]    findAll()
 * @method VoucherEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherRepository extends EntityRepository
{
}
