<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\OrderMessage;
use App\Service\OrderVoucherService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class OrderMessageHandler implements MessageHandlerInterface
{
    protected LoggerInterface $logger;

    protected OrderVoucherService $orderVoucherService;

    public function __construct(LoggerInterface $logger, OrderVoucherService $orderVoucherService)
    {
        $this->logger = $logger;
        $this->orderVoucherService = $orderVoucherService;
    }

    public function __invoke(OrderMessage $orderMessage): void
    {
        $orderId = $orderMessage->getOrderId();
        $customerId = $orderMessage->getCustomerId();
        $amount = $orderMessage->getAmount();

        $this->logger->info("Message for order id \"{$orderId}\", customer id \"{$customerId}\" and total amount of \"{$amount}\" received!\n");

        $this->orderVoucherService->appoint($orderId, $customerId, $amount);
    }
}
