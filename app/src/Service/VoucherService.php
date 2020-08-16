<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderEntity;

class VoucherService
{
    private const MINIMUM_AMOUNT = '100';

    public function apply(OrderEntity $order): ?\stdClass
    {
        if (bccomp($order->getAmount(), self::MINIMUM_AMOUNT) < 0) {
            return null;
        }

        return new \stdClass();
    }
}
