<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\OrderEntity;
use App\Entity\VoucherEntity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers OrderEntity
 */
class OrderEntityTest extends KernelTestCase
{
    public function testEntity(): void
    {
        foreach ($this->getTestCases() as $row => $testCase) {
            $actual = $testCase['actual'];
            $order = new OrderEntity();
            self::assertInstanceOf(OrderEntity::class, $order);
            $order->setOrderId($actual['order_id']);
            $order->setCustomerId($actual['customer_id']);
            $order->setStatus($actual['status']);
            $order->setVoucher($actual['voucher']);

            $expected = $testCase['expected'];
            self::assertEquals($expected['order_id'], $order->getOrderId(), sprintf('Test case "%s" failed asserting the correct order id!', $row));
            self::assertEquals($expected['customer_id'], $order->getCustomerId(), sprintf('Test case "%s" failed asserting the correct customer id!', $row));
            self::assertEquals($expected['status'], $order->getStatus(), sprintf('Test case "%s" failed asserting the correct state!', $row));
            self::assertInstanceOf($expected['voucher'], $order->getVoucher(), sprintf('Test case "%s" failed asserting the correct instance of voucher entity!', $row));
        }
    }

    private function getTestCases(): array
    {
        $cases[0] = [
            'actual' => [
                'id' => 1,
                'order_id' => 1,
                'customer_id' => 1,
                'status' => 'new',
                'voucher' => new VoucherEntity()
            ],
            'expected' => [
                'exception' => false,
                'id' => 1,
                'order_id' => 1,
                'customer_id' => 1,
                'status' => 'new',
                'voucher' => VoucherEntity::class
            ],
        ];

        return $cases;
    }
}
