<?php
declare(strict_types=1);

namespace App\Message;

class OrderMessage
{
    private int $orderId;

    private int $customerId;

    private string $amount;

    public function __construct(int $orderId, int $customerId, string $amount)
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->amount = $amount;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }
}
