<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\OrderEntity;
use App\Entity\VoucherEntity;
use App\Tests\Traits\EntityManagerAwareTrait;
use App\Tests\Traits\LoggerAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversNothing
 */
abstract class AbstractKernelTestCase extends KernelTestCase
{
    use EntityManagerAwareTrait;
    use LoggerAwareTrait;

    protected const CLASSES = [
        OrderEntity::class,
        VoucherEntity::class,
    ];

    protected LoggerInterface $logger;

    protected EntityManagerInterface $entityManager;

    protected ObjectRepository $orderRepository;

    protected ObjectRepository $voucherRepository;

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
        $this->orderRepository = $this->entityManager->getRepository(OrderEntity::class);
        $this->voucherRepository = $this->entityManager->getRepository(VoucherEntity::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
    }
}
