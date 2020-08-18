<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\OrderVoucherService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrderVoucherCreateCommand extends Command
{
    protected static $defaultName = 'app:order-voucher:create';

    private OrderVoucherService $orderVoucherService;

    public function __construct(OrderVoucherService $orderVoucherService)
    {
        parent::__construct();
        $this->orderVoucherService = $orderVoucherService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderId = time();
        $customerId = random_int(100, 999);
        $amount = (string)(random_int(9998, 10002) / 100);
        $output->writeln("Generating voucher for order id \"{$orderId}\", customer id \"{$customerId}\" and a total order amount of \"{$amount}\"");
        $order = $this->orderVoucherService->appoint($orderId, $customerId, $amount);

        if (null === $order) {
            return Command::FAILURE;
        }

        if (null === $order->getVoucher()) {
            $output->writeln('Order processed but no voucher generated!');

            return Command::SUCCESS;
        }

        $output->writeln("Order processed and voucher code \"{$order->getVoucher()->getCode()}\" generated!");

        return Command::SUCCESS;
    }
}
