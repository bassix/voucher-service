<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\OrderEntity;
use App\Repository\OrderRepository;
use App\Repository\VoucherRepository;
use App\Service\OrderService;

/**
 * @covers OrderService
 */
class OrderServiceTest extends AbstractKernelTestCase
{
    public function testCreateAndExists(): void
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->orderRepository;
        /** @var VoucherRepository $voucherRepository */
        $voucherRepository = $this->voucherRepository;

        $orderService = new OrderService($this->logger, $this->entityManager, $orderRepository, $voucherRepository);

        // @todo we need more sharp test data!
        $orderId = time();
        $customerId = random_int(100, 999);
        $amount = (string)(random_int(9998, 10002) / 100);

        // First we create a new order...
        $order = $orderService->create($orderId, $customerId, $amount);
        self::assertInstanceOf(OrderEntity::class, $order);

        // This order should not be found inside the database...
        self::assertFalse($orderService->exists($orderId, $customerId));

        // Now the order is stored into the database...
        self::assertTrue($orderService->store($order));

        // This order should now be found inside the database...
        self::assertTrue($orderService->exists($orderId, $customerId));
    }
}
