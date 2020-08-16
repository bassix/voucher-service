<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\OrderEntity;
use App\Service\VoucherService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TypeError;

class VoucherServiceTest extends KernelTestCase
{
    public function testInstantiation(): void
    {
        $voucherService = new VoucherService();
        self::assertInstanceOf(VoucherService::class, $voucherService);
    }

    public function testApplyToOrder(): void
    {
        $voucherService = new VoucherService();
        $order = new OrderEntity();

        // Test a empty order gets now voucher!
        $voucher = $voucherService->apply($order);
        self::assertNull($voucher);

        // Test a order with less then required 100€ order amount is not getting a voucher!
        $order->setAmount('99.999999999999999');
        $voucher = $voucherService->apply($order);
        self::assertNull($voucher);

        // Test a order with correct 100€ order amount is getting a voucher!
        $order->setAmount('100');
        $voucher = $voucherService->apply($order);
        self::assertInstanceOf(\stdClass::class, $voucher);

        $this->expectException(TypeError::class);
        $voucherService->apply(null);
    }
}
