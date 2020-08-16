<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderEntity;
use App\Entity\VoucherEntity;
use App\Repository\VoucherRepository;
use Doctrine\ORM\EntityManagerInterface;

class VoucherService
{
    public const CODE_LENGTH = 8;

    private const MINIMUM_AMOUNT = '100';

    private EntityManagerInterface $entityManager;

    private VoucherRepository $voucherRepository;

    public function __construct(EntityManagerInterface $entityManager, VoucherRepository $voucherRepository)
    {
        $this->entityManager = $entityManager;
        $this->voucherRepository = $voucherRepository;
    }

    public function apply(OrderEntity $order): ?VoucherEntity
    {
        if (bccomp($order->getAmount(), self::MINIMUM_AMOUNT) < 0) {
            return null;
        }

        do {
            $voucherCode = $this->generateCode();
            $voucherCount = $this->voucherRepository->count(['code' => $voucherCode]);
        } while (1 <= $voucherCount);

        $voucher = (new VoucherEntity())->setOrder($order)->setCode($this->generateCode());
        $this->entityManager->persist($voucher);

        return $voucher;
    }

    protected function generateCode()
    {
        return substr(md5((string)mt_rand()), 0, self::CODE_LENGTH);
    }
}
