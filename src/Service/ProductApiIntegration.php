<?php

declare(strict_types=1);

namespace App\Service;
class ProductApiIntegration
{
    private ProductApiClient $orderClient;

    public function __construct(ProductApiClient $apiClient) {
        $this->orderClient = $apiClient;
    }


}