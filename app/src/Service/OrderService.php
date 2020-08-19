<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\OrderEntity;

class OrderService extends AbstractService
{
    public function create(int $orderId, int $customerId, string $amount): ?OrderEntity
    {
        $order = (new OrderEntity())
            ->setOrderId($orderId)
            ->setCustomerId($customerId)
            ->setAmount($amount)
            ->setStatus('new');
        $this->entityManager->persist($order);

        /*
        try {
            $this->entityManager->persist($order);
        } catch (\Exception $exception) {
            // @todo Some error handling required!
            return null;
        }
        */

        return $order;
    }

    public function exists(int $orderId, int $customerId): bool
    {
        return $this->orderRepository->count(['orderId' => $orderId, 'customerId' => $customerId]) >= 1;
    }

    public function store(OrderEntity $order): bool
    {
        $this->entityManager->flush();

        /*
        try {
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            // @todo Some error handling required!
            return false;
        }
        */

        return true;
    }
}
