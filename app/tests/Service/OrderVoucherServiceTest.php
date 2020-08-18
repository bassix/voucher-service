<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\OrderEntity;
use App\Repository\OrderRepository;
use App\Repository\VoucherRepository;
use App\Service\OrderService;
use App\Service\OrderVoucherService;
use App\Service\VoucherService;

/**
 * @covers OrderVoucherService
 */
class OrderVoucherServiceTest extends AbstractKernelTestCase
{
    public function testOrderVoucher(): void
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->orderRepository;
        /** @var VoucherRepository $voucherRepository */
        $voucherRepository = $this->voucherRepository;

        $orderService = new OrderService($this->logger, $this->entityManager, $orderRepository, $voucherRepository);
        $voucherService = new VoucherService($this->logger, $this->entityManager, $orderRepository, $voucherRepository);
        $orderVoucherService = new OrderVoucherService($this->logger, $this->entityManager, $orderRepository, $voucherRepository, $orderService, $voucherService);

        // Create a empty test order...
        $order = $orderVoucherService->appoint(0, 0, '0.0');
        self::assertInstanceOf(OrderEntity::class, $order);
    }
}
