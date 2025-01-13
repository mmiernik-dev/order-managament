<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Dto\CreateOrder;
use App\Entity\Order;
use App\Entity\ValueObject\Product;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase {
    private OrderService $orderService;
    private OrderRepository $orderRepositoryMock;

    protected function setUp(): void
    {
        $this->orderRepositoryMock = $this->createMock(OrderRepository::class);
        $this->orderService = new OrderService($this->orderRepositoryMock);

        //Mock save action - it is used in all actions
        $this->orderRepositoryMock->expects($this->once())
            ->method('save');
    }

    public function testItCreateANewOrder(): void
    {
        //Preparing DTO
        $products =  [
            ['id' => 1,  "title" => "Product 1",  "price" => 2.04],
            ['id' => 2,  "title" => "Product 2",  "price" => 100],
        ];

        $createOrderDto = new CreateOrder();
        $createOrderDto->username = 'Test User';
        $createOrderDto->products = $products;

        $createdDate = new DateTimeImmutable();
        //Create a new order
        $order = $this->orderService->createNewOrder($createOrderDto);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('Test User', $order->getUsername());
        $this->assertEquals(OrderStatus::PENDING->value, $order->getStatus());

        //check if products are correctly saved
        $this->assertCount(2, $order->getProducts());
        $this->assertCheckProduct($order->getProducts(), $products);

        //verify the creation date (minutes accurate).
        $this->assertEqualsWithDelta(
            $createdDate->getTimestamp(),
            $order->getCreatedAt()->getTimestamp(),
            60,
            'Dates do not match within one minute.'
        );
    }

    public function testItCancelExistedOrder(): void
    {
        // Prepare Order
        $order = new Order('Test User');

        // Cancel Order
        $this->orderService->cancelOrder($order);

        //Only status should be changed
        $this->assertEquals(OrderStatus::CANCEL->value, $order->getStatus());
    }

    public function testItRecreateNewOrderFromExisted(): void
    {
        $products =  [
            ['id' => 5,  "title" => "Product for new  1",  "price" => 5.99],
            ['id' => 12,  "title" => "Product for new 2",  "price" => 6.87],
        ];
        // Prepare Order
        $existedOrder = new Order('John Doe');
        $existedOrder->setProducts($products);

        // Recreate Order
        $newOrder = $this->orderService->recreateOrder($existedOrder);

        $this->assertInstanceOf(Order::class, $newOrder);
        $this->assertNull($newOrder->getId(), 'Id order should not be copied');


        $this->assertCheckProduct($newOrder->getProducts(), $products);

        $this->assertEquals($existedOrder->getUsername(), $newOrder->getUsername() );
        $this->assertEquals(OrderStatus::PENDING->value, $newOrder->getStatus());
    }

    private function assertCheckProduct(array $fetchedProducts, array $expectedProducts): void
    {
        foreach ($fetchedProducts as $key => $product) {
            $this->assertInstanceOf(Product::class, $product);
            $this->assertEquals($expectedProducts[$key]['price'], $product->getPrice());
            $this->assertEquals($expectedProducts[$key]['title'], $product->getTitle());
        }
    }
}