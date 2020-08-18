<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\OrderMessage;
use App\Service\OrderService;
use App\Service\VoucherService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class OrderMessageHandler implements MessageHandlerInterface
{
    protected LoggerInterface $logger;

    protected OrderService $orderService;

    protected VoucherService $voucherService;

    public function __construct(LoggerInterface $logger, OrderService $orderService, VoucherService $voucherService)
    {
        $this->logger = $logger;
        $this->orderService = $orderService;
        $this->voucherService = $voucherService;
    }

    public function __invoke(OrderMessage $orderMessage): void
    {
        $orderId = $orderMessage->getOrderId();
        $customerId = $orderMessage->getCustomerId();
        $amount = $orderMessage->getAmount();

        $this->logger->notice("Message for order id \"{$orderId}\", customer id \"{$customerId}\" and total amount of \"{$amount}\" received!\n");

        if ($this->orderService->exists($orderId, $customerId)) {
            $this->logger->notice("Order exists and already processed!\n");
        }

        // Create a new order...
        $order = $this->orderService->createNew($orderId, $customerId, $amount);

        if (null === $order) {
            $this->logger->error("Unexpected situation! Unable to persist and store the order!\n");

            return;
        }

        // Apply a voucher to the order...
        $voucher = $this->voucherService->apply($order);

        if ($this->orderService->store($order)) {
            $this->logger->error("Unexpected situation! Unable to persist and store the order with a voucher!\n");

            return;
        }

        if (null !== $voucher) {
            $this->logger->notice("Voucher to order added, new voucher code \"{$voucher->getCode()}\" generated!\n");
        } else {
            $this->logger->notice("Voucher to order added but no voucher code generated!\n");
        }
    }
}
