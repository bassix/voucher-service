<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderEntity;
use App\Repository\OrderRepository;
use App\Repository\VoucherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class OrderVoucherService extends AbstractService
{
    private OrderService $orderService;

    private VoucherService $voucherService;

    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        OrderRepository $orderRepository,
        VoucherRepository $voucherRepository,
        OrderService $orderService,
        VoucherService $voucherService
    ) {
        parent::__construct($logger, $entityManager, $orderRepository, $voucherRepository);
        $this->orderService = $orderService;
        $this->voucherService = $voucherService;
    }

    public function appoint($orderId, $customerId, $amount): ?OrderEntity
    {
        if ($this->orderService->exists($orderId, $customerId)) {
            $this->logger->info("Order exists and already processed!\n");

            return null;
        }

        // Create a new order...
        $order = $this->orderService->create($orderId, $customerId, $amount);

        if (null === $order) {
            $this->logger->error("Unexpected situation! Unable to persist and store the order!\n");

            return null;
        }

        // Apply a voucher to the order...
        $voucher = $this->voucherService->apply($order);

        if (null === $voucher) {
            $this->logger->info("Voucher to order added but no voucher code generated!\n");

            return $order;
        }

        $this->logger->info("Voucher to order added, new voucher code \"{$voucher->getCode()}\" generated!\n");

        if ($this->orderService->store($order)) {
            $this->logger->error("Unexpected situation! Unable to persist and store the order with a voucher!\n");

            return null;
        }

        return $order;
    }
}
