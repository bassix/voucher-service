<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\OrderRepository;
use App\Repository\VoucherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractService
{
    protected LoggerInterface $logger;

    protected EntityManagerInterface $entityManager;

    protected OrderRepository $orderRepository;

    protected VoucherRepository $voucherRepository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, OrderRepository $orderRepository, VoucherRepository $voucherRepository)
    {
        $this->logger = $logger;

        /*
         * Note: this is a workaround because of following error:
         * Error thrown while handling message App\Message\OrderMessage. Sending for retry #1 using 1000 ms delay.
         * Error: "The EntityManager is closed." {"message":{"App\\Message\\OrderMessage":[]},"class":"App\\Message\\OrderMessage","retryCount":1,"delay":1000,"error":"The EntityManager is closed.","exception":"[object] (Symfony\\Component\\Messenger\\Exception\\HandlerFailedException(code: 0): The EntityManager is closed. at /srv/app/vendor/symfony/messenger/Middleware/HandleMessageMiddleware.php:80)\n[previous exception] [object] (Doctrine\\ORM\\ORMException(code: 0): The EntityManager is closed. at /srv/app/vendor/doctrine/orm/lib/Doctrine/ORM/ORMException.php:146)"
         */
        if (!$entityManager->isOpen()) {
            $entityManager = $entityManager->create($entityManager->getConnection(), $entityManager->getConfiguration());
        }

        $this->entityManager = $entityManager;
        $this->voucherRepository = $voucherRepository;
    }
}
