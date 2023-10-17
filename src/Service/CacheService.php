<?php

declare(strict_types=1);

namespace App\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final class CacheService
{
    public function __construct(private readonly TagAwareCacheInterface $cache, private readonly SerializerInterface $serializer, private readonly SerializationContext $serializerContext)
    {
    }

    public function getCache(string $idCache, mixed $resource, string $tag, mixed $groups): mixed
    {
        return $this->cache->get(
            $idCache,
            function (ItemInterface $item) use ($resource, $tag, $groups): string {
                $item->tag($tag);

                return $this->serializer->serialize($resource, 'json', $this->serializerContext::create()->setGroups($groups)->setSerializeNull(true));
            }
        );
    }

    public function deleteCache(array $tags): void
    {
        $this->cache->invalidateTags($tags);
    }
}
