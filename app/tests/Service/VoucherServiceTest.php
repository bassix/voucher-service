<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\OrderEntity;
use App\Entity\VoucherEntity;
use App\Repository\OrderRepository;
use App\Repository\VoucherRepository;
use App\Service\VoucherService;
use Error;
use TypeError;

/**
 * @covers VoucherService
 */
class VoucherServiceTest extends AbstractKernelTestCase
{
    public function testApplyVoucherToOrder(): void
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->orderRepository;
        /** @var VoucherRepository $voucherRepository */
        $voucherRepository = $this->voucherRepository;
        $voucherService = new VoucherService($this->logger, $this->entityManager, $orderRepository, $voucherRepository);

        // Create a empty order and expect a exception...
        $order = new OrderEntity();
        $this->expectException(Error::class);
        $voucherService->apply($order);

        // Test a empty order gets no voucher!
        $order->setOrderId(0)->setCustomerId(0);
        $voucher = $voucherService->apply($order);
        self::assertNull($voucher);

        // Test a order with less then required 100€ order amount is not getting a voucher!
        $order->setAmount('99.999999999999999');
        $voucher = $voucherService->apply($order);
        self::assertNull($voucher);

        // Test a order with correct 100€ order amount is getting a voucher!
        $order->setAmount('100');
        $voucher = $voucherService->apply($order);
        self::assertInstanceOf(VoucherEntity::class, $voucher);
        self::assertNotEmpty($voucher->getCode());
        self::assertEquals(VoucherService::CODE_LENGTH, strlen($voucher->getCode()));

        $this->expectException(TypeError::class);
        $voucherService->apply(null);
    }
}
