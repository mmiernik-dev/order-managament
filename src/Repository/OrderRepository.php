<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public const ALIAS = 'order';
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Order::class);
        $this->logger = $logger;
    }

    public function save(Order $order): void
    {
        try {
            $this->getEntityManager()->persist($order);
            $this->getEntityManager()->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Failed to save order: ' . $exception->getMessage(), [
                'exception' => $exception,
                'order' => $order,
            ]);
            throw new \RuntimeException('An error occurred while saving the order.');
        }
    }

    public function remove(Order $order): void
    {
        try {
            $this->getEntityManager()->remove($order);
            $this->getEntityManager()->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Failed to save order: ' . $exception->getMessage(), [
                'exception' => $exception,
                'order' => $order,
            ]);
            throw new \RuntimeException('An error occurred while saving the order.');
        }
    }
}
