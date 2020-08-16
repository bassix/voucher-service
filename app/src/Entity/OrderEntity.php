<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="`order`",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="unique_order_customer_id", columns={"order_id", "customer_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class OrderEntity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned" = true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(name="order_id", type="integer", nullable=false, options={"unsigned" = true})
     */
    private int $orderId;

    /**
     *
     * @ORM\Column(name="customer_id", type="integer", nullable=false, options={"unsigned" = true})
     */
    private int $customerId;

    /**
     *
     * @ORM\Column(name="amount", type="decimal", precision=20, scale=4, nullable=false)
     */
    private string $amount = '0';

    public function getId(): int
    {
        return $this->id;
    }

    public function setOrderId(int $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }
}
