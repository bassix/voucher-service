<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderEntity;

class OrderService extends AbstractService
{
    public function createNew(int $orderId, int $customerId, string $amount): ?OrderEntity
    {
        try {
            $order = (new OrderEntity())
                ->setOrderId($orderId)
                ->setCustomerId($customerId)
                ->setAmount($amount)
                ->setStatus('new');
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            // Some error handling required!
            return null;
        }

        return $order;
    }

    public function exists(int $orderId, int $customerId): bool
    {
        return $this->entityManager->getRepository(OrderEntity::class)->count(['order_id' => $orderId, 'customer_id' => $customerId]) >= 1;
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
