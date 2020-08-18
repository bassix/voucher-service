<?php
declare(strict_types=1);

namespace App\Tests\Traits;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

trait LoggerAwareTrait
{
    protected function getLogger(KernelInterface $kernel): LoggerInterface
    {
        $container = $kernel->getContainer();
        /** @var LoggerInterface $logger */
        $logger = $container->get('logger');

        return $logger;
    }
}
