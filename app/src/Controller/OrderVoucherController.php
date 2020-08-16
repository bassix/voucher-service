<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\OrderMessage;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderVoucherController extends AbstractController
{
    /**
     * @Route(name="order", path="order")
     * @throws Exception
     */
    public function placeOrder(MessageBusInterface $bus): Response
    {
        $bus->dispatch(new OrderMessage(time(), random_int(100, 999), (string)(rand(9998, 10002) / 100)));

        return new Response('<html><body>Order is placed!</body></html>');
    }
}
