<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderEntity;
use App\Entity\VoucherEntity;

class VoucherService
{
    private const MINIMUM_AMOUNT = '100';

    public function apply(OrderEntity $order): ?VoucherEntity
    {
        if (bccomp($order->getAmount(), self::MINIMUM_AMOUNT) < 0) {
            return null;
        }

        return new VoucherEntity();
    }
}
