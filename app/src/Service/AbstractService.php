<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\OrderRepository;
use App\Repository\VoucherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractService
{
    protected LoggerInterface $logger;

    protected EntityManagerInterface $entityManager;

    protected OrderRepository $orderRepository;

    protected VoucherRepository $voucherRepository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, OrderRepository $orderRepository, VoucherRepository $voucherRepository)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
        $this->voucherRepository = $voucherRepository;
    }
}
