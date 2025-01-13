<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Request;

class ProductApiClient
{
    const CACHE_KEY = 'products_list';
    private Client $client;
    private RedisService $redisService;

    public function __construct(RedisService $redisService, string $apiUrl) {
        $this->client = new Client(['base_uri' => $apiUrl]);

        $this->redisService = $redisService;
    }

    public function fetchProducts()
    {
        try {
            $content = $this->redisService->getData(self::CACHE_KEY);

            if (null === $content) {
                $response = $this->doRequest('/products/');
                $content = $response->getBody()->getContents();
            }
            $this->redisService->saveData(self::CACHE_KEY, $content, 3600);
            return json_decode($content, true);

        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    public function fetchOneById(int $id)
    {
        try {
            $content = $this->doRequest('/products/' . $id)
                    ->getBody()
                    ->getContents();
            return json_decode($content, true);
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @throws /GuzzleException
     */
    private function doRequest(string $url, array $params = []): Response
    {
        return $this->client->request(Request::METHOD_GET, $url, $params);
    }
}
