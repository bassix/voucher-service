<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\OrderEntity;
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

        if ($this->orderService->exists($orderId, $customerId)) {
            $this->logger->notice("Order with id \"{$orderId}\" for customer id \"{$customerId}\" already processed!\n");
        }

        $order = (new OrderEntity())
            ->setOrderId($orderId)
            ->setCustomerId($customerId)
            ->setAmount($amount);
        $voucher = $this->voucherService->apply($order);

        if ($this->orderService->store($order)) {
            // @todo Send email to customer with voucher code!
        }

        $this->logger->notice("Order with id \"{$orderId}\" for customer id \"{$customerId}\" and total amount of \"{$amount}\" was placed!\n");

        if (null !== $voucher) {
            $this->logger->notice("New voucher code \"{$voucher->getCode()}\" generated!\n");
        }
    }
}
