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
            $this->logger->info("Order with order id \"{$orderId}\" and customer id \"{$customerId}\" exists and already processed!");

            return null;
        }

        // Create a new order...
        $order = $this->orderService->create($orderId, $customerId, $amount);

        if (null !== $order) {
            $this->logger->info("Order with order id \"{$orderId}\" and customer id \"{$customerId}\" successful created!");
        } else {
            $this->logger->error("Unable to create order with order id \"{$orderId}\" and customer id \"{$customerId}\"!");

            return null;
        }

        // Apply a voucher to the order...
        $voucher = $this->voucherService->apply($order);

        if (null !== $voucher) {
            $order->setVoucher($voucher)->setStatus('processed');
            $this->logger->info('Voucher added to order!');
        } else {
            $order->setStatus('discarded');
            $this->logger->info('No voucher generated!');
        }

        // Store all persisted entities into the database...
        if ($this->orderService->store($order)) {
            $this->logger->info('The order stored successful!');
        } else {
            $this->logger->error('Storage error, unable to store the persisted order and voucher!');

            return null;
        }

        return $order;
    }
}
