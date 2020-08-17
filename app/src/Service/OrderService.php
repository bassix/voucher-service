<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderEntity;

class OrderService extends AbstractService
{
    public function exists(int $orderId, int $customerId): bool
    {
        return false;
    }

    public function store(OrderEntity $order): bool
    {
        try {
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            // Some error handling required!
            return false;
        }

        return true;
    }
}
