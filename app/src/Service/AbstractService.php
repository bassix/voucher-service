<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\VoucherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractService
{
    protected LoggerInterface $logger;

    protected EntityManagerInterface $entityManager;

    protected VoucherRepository $voucherRepository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, VoucherRepository $voucherRepository)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->voucherRepository = $voucherRepository;
    }
}
