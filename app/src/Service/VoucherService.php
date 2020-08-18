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
        if (0 > bccomp($order->getAmount(), self::MINIMUM_AMOUNT)) {
            $this->logger->info("Voucher code not generated for order id \"{$order->getOrderId()}\", customer id \"{$order->getCustomerId()}\" because the total amount of \"{$order->getAmount()}\" is to low!\n");

            return null;
        }

        do {
            $voucherCode = $this->generateCode();
            $voucherCount = $this->voucherRepository->count(['code' => $voucherCode]);
        } while (1 <= $voucherCount);

        $voucher = (new VoucherEntity())->setCode($voucherCode);
        $this->entityManager->persist($voucher);
        $order->setVoucher($voucher)->setStatus('generated');
        $this->logger->info("Voucher code \"{$voucherCode}\" for order id \"{$order->getOrderId()}\" and customer id \"{$order->getCustomerId()}\" with total amount of \"{$order->getAmount()}\" was placed!\n");

        return $voucher;
    }

    protected function generateCode()
    {
        return substr(md5((string)mt_rand()), 0, self::CODE_LENGTH);
    }
}
