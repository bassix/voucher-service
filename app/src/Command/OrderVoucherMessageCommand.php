<?php

namespace App\Command;

use App\Message\OrderMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderVoucherMessageCommand extends Command
{
    protected static $defaultName = 'app:order-voucher-message';

    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();

        $this->bus = $bus;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Note: This is only a helper command to place a fake order and generate a message into the message bus!');

        $orderId = time();
        $customerId = random_int(100, 999);
        $amount = (string)(random_int(9998, 10002) / 100);
        $output->writeln("Generating order message with order id \"{$orderId}\", customer id \"{$customerId}\" and a total order amount of \"{$amount}\"");
        $this->bus->dispatch(new OrderMessage($orderId, $customerId, $amount));

        return Command::SUCCESS;
    }
}
