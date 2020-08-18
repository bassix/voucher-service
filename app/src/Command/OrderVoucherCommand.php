<?php
declare(strict_types=1);

namespace App\Command;

use App\Message\OrderMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderVoucherCommand extends Command
{
    protected static $defaultName = 'app:order-voucher';

    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();

        $this->bus = $bus;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bus->dispatch(new OrderMessage(time(), random_int(100, 999), (string)(rand(9998, 10002) / 100)));
        $output->writeln('Order is placed!');

        return Command::SUCCESS;
    }
}
