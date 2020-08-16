<?php
declare(strict_types=1);

namespace App\Tests\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

trait EntityManagerAwareTrait
{
    protected function getEntityManager(KernelInterface $kernel): EntityManagerInterface
    {
        $container = $kernel->getContainer();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');

        return $entityManager;
    }
}
