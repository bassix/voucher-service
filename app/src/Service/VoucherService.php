<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderEntity;
use App\Entity\VoucherEntity;

class VoucherService extends AbstractService
{
    public const CODE_LENGTH = 8;

    private const MINIMUM_AMOUNT = '100';

    public function apply(OrderEntity $order): ?VoucherEntity
    {
        if (bccomp($order->getAmount(), self::MINIMUM_AMOUNT) <= 0) {
            $this->logger->notice("Voucher code not generated for order id \"{$order->getOrderId()}\" and customer id \"{$order->getCustomerId()}\" with to low total amount of \"{$order->getAmount()}\"!\n");

            return null;
        }

        do {
            $voucherCode = $this->generateCode();
            $voucherCount = $this->voucherRepository->count(['code' => $voucherCode]);
        } while (1 <= $voucherCount);

        $voucher = (new VoucherEntity())->setOrder($order)->setCode($this->generateCode());
        $this->entityManager->persist($voucher);
        $this->entityManager->flush();

        $this->logger->notice("Voucher code \"{$voucherCode}\" for order id \"{$order->getOrderId()}\" and customer id \"{$order->getCustomerId()}\" with total amount of \"{$order->getAmount()}\" was placed!\n");

        return $voucher;
    }

    protected function generateCode()
    {
        return substr(md5((string)mt_rand()), 0, self::CODE_LENGTH);
    }
}
