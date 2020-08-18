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
     * @ORM\Column(name="status", type="string", length=45, nullable=false)
     */
    private string $status;

    /**
     *
     * @ORM\Column(name="amount", type="decimal", precision=20, scale=4, nullable=false)
     */
    private string $amount;

    /**
     * One order can only have one voucher.
     *
     * @ORM\OneToOne(targetEntity="VoucherEntity", inversedBy="order", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="voucher_id", referencedColumnName="id", nullable=true)
     */
    private ?VoucherEntity $voucher;

    public function __construct()
    {
        $this->status = 'new';
        $this->amount = '0.0';
        $this->voucher = null;
    }

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

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
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

    public function setVoucher(VoucherEntity $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }

    public function getVoucher(): ?VoucherEntity
    {
        return $this->voucher;
    }
}
