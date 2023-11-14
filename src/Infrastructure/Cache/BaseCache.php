<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Cache;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class BaseCache
{
    public const CACHE_TIME = 36000; // 10 horas
    public const CACHE_TIME_SHORT = 3600; // 1 hora
    public const CACHE_TIME_LONG = 864000; // 864000; // 10 dias

    public function __construct(private readonly TagAwareCacheInterface $baseCache)
    {
    }

    public function get(string $key, callable $callback, array $tags = [], int $time = self::CACHE_TIME)
    {
        return $this->baseCache->get($key, function (ItemInterface $item) use ($callback, $tags, $time) {
            $item->tag($tags);
            $item->expiresAfter($time);

            return \call_user_func($callback);
        });
    }

    public function delete(string $key): bool
    {
        try {
            return $this->baseCache->delete($key);
        } catch (InvalidArgumentException) {
        }

        return false;
    }

    public function deleteTags(array $tags): bool
    {
        try {
            return $this->baseCache->invalidateTags($tags);
        } catch (InvalidArgumentException) {
        }

        return false;
    }

    public function cache(): CacheInterface
    {
        return $this->baseCache;
    }
}
