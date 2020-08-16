<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderEntity;
use App\Entity\VoucherEntity;
use App\Repository\OrderRepository;
use App\Repository\VoucherRepository;
use Doctrine\ORM\EntityManagerInterface;

class VoucherService
{
    public const CODE_LENGTH = 8;

    private const MINIMUM_AMOUNT = '100';

    private VoucherRepository $voucherRepository;

    public function __construct(VoucherRepository $voucherRepository)
    {
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

        return $voucher;
    }

    protected function generateCode()
    {
        return substr(md5((string)mt_rand()), 0, self::CODE_LENGTH);
    }
}
