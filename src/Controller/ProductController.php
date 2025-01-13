<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ProductApiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductController extends AbstractController
{
    private ProductApiClient $apiClient;
    public function __construct(ProductApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    #[Route('', methods: ['GET'])]
    public function fetchProducts(): JsonResponse
    {
        $products = $this->apiClient->fetchProducts();

        return $this->json($products);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function fetchSingleProduct(int $id): JsonResponse
    {
        $products = $this->apiClient->fetchOneById($id);

        return $this->json($products);
    }
}