<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\OrderMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class OrderMessageHandler implements MessageHandlerInterface
{
    public function __invoke(OrderMessage $orderMessage): void
    {
        $orderId = $orderMessage->getOrderId();
        $customerId = $orderMessage->getCustomerId();
        $amount = $orderMessage->getAmount();

        // We only want to simulate a long running process...
        sleep(1);

        echo "Order with id \"{$orderId}\" for customer id \"{$customerId}\" and total amount of \"{$amount}\" was placed!\n";
    }
}
