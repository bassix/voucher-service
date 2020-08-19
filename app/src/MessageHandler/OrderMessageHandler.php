<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\OrderMessage;
use App\Service\OrderVoucherService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class OrderMessageHandler implements MessageHandlerInterface
{
    protected LoggerInterface $logger;

    protected EntityManagerInterface $entityManager;

    protected OrderVoucherService $orderVoucherService;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, OrderVoucherService $orderVoucherService)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->orderVoucherService = $orderVoucherService;
    }

    public function __destruct()
    {
        $this->entityManager->clear();
    }

    public function __invoke(OrderMessage $orderMessage): void
    {
        $orderId = $orderMessage->getOrderId();
        $customerId = $orderMessage->getCustomerId();
        $amount = $orderMessage->getAmount();

        $this->logger->info("Message for order id \"{$orderId}\", customer id \"{$customerId}\" and total amount of \"{$amount}\" received!\n");

        /*
         * So we put duck tape on a problem:
         * https://github.com/symfony/symfony/issues/35360
         *
         * Note: this is a workaround because of following error:
         * Error thrown while handling message App\Message\OrderMessage. Sending for retry #1 using 1000 ms delay.
         * Error: "The EntityManager is closed." {"message":{"App\\Message\\OrderMessage":[]},"class":"App\\Message\\OrderMessage","retryCount":1,"delay":1000,"error":"The EntityManager is closed.","exception":"[object] (Symfony\\Component\\Messenger\\Exception\\HandlerFailedException(code: 0): The EntityManager is closed. at /srv/app/vendor/symfony/messenger/Middleware/HandleMessageMiddleware.php:80)\n[previous exception] [object] (Doctrine\\ORM\\ORMException(code: 0): The EntityManager is closed. at /srv/app/vendor/doctrine/orm/lib/Doctrine/ORM/ORMException.php:146)"
         */

        if (!$this->entityManager->isOpen()) {
            $this->entityManager = $this->entityManager::create(
                $this->entityManager->getConnection(),
                $this->entityManager->getConfiguration(),
                $this->entityManager->getEventManager()
            );
        }

        try {
            $this->orderVoucherService->appoint($orderId, $customerId, $amount);
        } catch (ORMException $exception) {
            // No codes no nothing... no comment
            if ('The EntityManager is closed.' === $exception->getMessage()) {
                dd('Closed because entity manager needed to be reset!');
            }

            throw $exception;
        }
    }
}
