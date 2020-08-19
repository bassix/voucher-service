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
            $this->logger->info("Aborting voucher generation, the order total amount of \"{$order->getAmount()}\" is to low! ");

            return null;
        }

        do {
            $voucherCode = $this->generateCode();
            $voucherCount = $this->voucherRepository->count(['code' => $voucherCode]);
        } while (1 <= $voucherCount);

        $voucher = (new VoucherEntity())
            ->setCode($voucherCode)
            ->setStatus('generated');
        $this->entityManager->persist($voucher);
        $this->logger->info("Voucher code \"{$voucherCode}\" for order generated!");

        return $voucher;
    }

    protected function generateCode()
    {
        return substr(md5((string)mt_rand()), 0, self::CODE_LENGTH);
    }
}
