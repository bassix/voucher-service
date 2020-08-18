<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\OrderEntity;
use App\Entity\VoucherEntity;
use App\Repository\OrderRepository;
use App\Repository\VoucherRepository;
use App\Service\VoucherService;
use App\Tests\Traits\EntityManagerAwareTrait;
use App\Tests\Traits\LoggerAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TypeError;

/**
 * @covers VoucherService
 */
class VoucherServiceTest extends KernelTestCase
{
    use EntityManagerAwareTrait;
    use LoggerAwareTrait;

    private const CLASSES = [
        OrderEntity::class,
        VoucherEntity::class,
    ];

    private LoggerInterface $logger;

    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $classes = [];
        $kernel = self::bootKernel();
        $this->logger = $this->getLogger($kernel);
        $this->entityManager = $this->getEntityManager($kernel);

        foreach (self::CLASSES as $class) {
            $classes[] = $this->entityManager->getClassMetadata($class);
        }

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema($classes);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
    }

    public function testApplyVoucherToOrder(): void
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->entityManager->getRepository(OrderEntity::class);
        /** @var VoucherRepository $voucherRepository */
        $voucherRepository = $this->entityManager->getRepository(VoucherEntity::class);
        $voucherService = new VoucherService($this->logger, $this->entityManager, $orderRepository, $voucherRepository);

        // Create a new and empty order...
        $order = (new OrderEntity())->setOrderId(0)->setCustomerId(0);

        // Test a empty order gets no voucher!
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
