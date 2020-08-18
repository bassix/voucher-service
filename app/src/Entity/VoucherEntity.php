<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="`voucher`"
 * )
 * @ORM\Entity(repositoryClass="App\Repository\VoucherRepository")
 */
class VoucherEntity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned" = true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=false)
     */
    private string $status;

    /**
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private string $code;

    public function getId(): int
    {
        return $this->id;
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

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
