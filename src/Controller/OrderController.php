<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreateOrder;
use App\Entity\Order;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/orders')]
class OrderController extends AbstractController
{
    private OrderService $orderService;

    public function __construct(
        OrderService $orderService
    ) {
        $this->orderService = $orderService;
    }

    #[Route('',  methods: [Request::METHOD_GET])]
    public function fetchOrders(): JsonResponse
    {
        $orders = $this->orderService->getAll();

        return $this->json(
            ['data' => $orders],
            Response::HTTP_CREATED
        );
    }

    #[Route('/{id}',  methods: [Request::METHOD_GET])]
    public function fetchOne(Order $order): JsonResponse
    {
        return $this->json(
            ['data' => $order],
            Response::HTTP_CREATED
        );
    }

    #[Route('',  methods: [Request::METHOD_POST])]
    public function createOrder(CreateOrder $createOrder): JsonResponse
    {
        $order = $this->orderService->createNewOrder($createOrder);

        return $this->json(
            ['message' => 'Order placed successfully', 'orderId' => $order->getId()],
            Response::HTTP_CREATED
        );
    }

    #[Route('/{id}/cancel',  methods: [Request::METHOD_DELETE])]
    public function cancelOrder(Order $order): JsonResponse
    {
        $this->orderService->cancelOrder($order);
        return $this->json(
            ['message' => 'Order cancelled successfully'],
        Response::HTTP_NO_CONTENT
        );
    }

    #[Route('/{id}/recreate', methods: [Request::METHOD_POST])]
    public function recreateOrder(Order $order): JsonResponse
    {
        $newOrder = $this->orderService->recreateOrder($order);

        return $this->json(
            ['message' => 'Order recreated successfully', 'orderId' => $newOrder->getId()]
        );
    }
}
