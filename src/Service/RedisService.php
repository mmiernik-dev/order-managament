<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisService
{
    private RedisAdapter $cache;

    public function __construct(string $redisUrl)
    {
        $redisConnection = RedisAdapter::createConnection($redisUrl);
        $this->cache = new RedisAdapter($redisConnection);
    }

    public function saveData(string $key, string $value, int $ttl = 3600): void
    {
        $cacheItem = $this->cache->getItem($key);
        $cacheItem->set($value);
        $cacheItem->expiresAfter($ttl);
        $this->cache->save($cacheItem);
    }

    public function getData(string $key): ?string
    {
        $cacheItem = $this->cache->getItem($key);
        return $cacheItem->isHit() ? $cacheItem->get() : null;
    }
}