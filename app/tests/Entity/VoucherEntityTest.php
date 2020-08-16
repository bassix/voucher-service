<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\OrderEntity;
use App\Entity\VoucherEntity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers VoucherEntity
 */
class VoucherEntityTest extends KernelTestCase
{
    public function testEntity(): void
    {
        foreach ($this->getTestCases() as $row => $testCase) {
            $actual = $testCase['actual'];
            $voucher = new VoucherEntity();
            self::assertInstanceOf(VoucherEntity::class, $voucher);
            $voucher->setStatus($actual['status']);
            $voucher->setCode($actual['code']);
            $voucher->setOrder($actual['order']);

            $expected = $testCase['expected'];
            self::assertEquals($expected['status'], $voucher->getStatus(), sprintf('Test case "%s" failed asserting the correct state!', $row));
            self::assertEquals($expected['code'], $voucher->getCode(), sprintf('Test case "%s" failed asserting the correct state!', $row));
            self::assertInstanceOf($expected['order'], $voucher->getOrder(), sprintf('Test case "%s" failed asserting instance of "%s"!', $row, $expected['order']));
        }
    }

    private function getTestCases(): array
    {
        $cases[0] = [
            'actual' => [
                'id' => 1,
                'status' => 'new',
                'code' => 'some-code',
                'order' => new OrderEntity(),
            ],
            'expected' => [
                'exception' => false,
                'id' => 1,
                'status' => 'new',
                'code' => 'some-code',
                'order' => OrderEntity::class,
            ],
        ];

        return $cases;
    }
}
