<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CreateOrder;
use App\Entity\Order;
use App\Entity\ValueObject\Product;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;

class OrderService
{
    private OrderRepository $orderRepository;
    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function getAll(): array
    {
        return $this->orderRepository->findAll();
    }

    public function createNewOrder(CreateOrder $createOrder): Order
    {
        $order = new Order($createOrder->username);
        $order->setProducts($this->createProductList($createOrder->products));

        $this->orderRepository->save($order);

        return $order;
    }

    public function cancelOrder(Order $order): void
    {
        //I decide for soft delete by status
        $order->setStatus(OrderStatus::CANCEL);
        $this->orderRepository->save($order);

        //OPTIONAL: Second option - hard remove
        // $this->orderRepository->remove($order);
    }

    public function recreateOrder(Order $existingOrder): Order
    {
        $newOrder = new Order($existingOrder->getUsername());
        $newOrder->setProducts($this->createProductList($existingOrder->getProducts()));

        $this->orderRepository->save($newOrder);

        return $newOrder;
    }

    private function createProductList(array $products): array
    {
        $listOfProducts = [];
        foreach ($products as $product) {
            if ($product instanceof Product) {
                $product = $product->toArray();
            }
            $newProduct = new Product(...$product);
            $listOfProducts[] = $newProduct;
        }

        return $listOfProducts;
    }
}
