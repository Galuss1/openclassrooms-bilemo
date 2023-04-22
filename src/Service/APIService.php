<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class APIService
{
    public function __construct(private readonly SerializerInterface $serializer, private readonly EntityManagerInterface $em, private readonly CacheService $cacheService)
    {
    }

    public function post(mixed $resource, string $location, array $groups): JsonResponse
    {
        $jsonResponse = $this->serialize($resource, $groups, null, null);

        return new JsonResponse(
            $jsonResponse,
            Response::HTTP_CREATED,
            [
                'Location' => $location,
            ],
            true
        );
    }

    public function get(mixed $resource, array $groups, ?string $idCache=null, ?string $tag=null): JsonResponse
    {
        if (!\is_array($resource) && !\is_object($resource)) {
            throw new \InvalidArgumentException('The resource must be an array or an object');
        }

        $jsonResponse = $this->serialize($resource, $groups, $idCache, $tag);

        return new JsonResponse(
            $jsonResponse,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json',
            ],
            true
        );
    }

    public function delete(object $resource, ?array $tags=null): JsonResponse
    {
        if (!\is_object($resource)) {
            throw new \InvalidArgumentException('The resource must be an object');
        }

        if ($tags) {
            $this->cacheService->deleteCache($tags);
        }

        $this->em->remove($resource);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return array{groups: mixed[], skip_null_values: true}
     */
    private function getOptions(array $groups): array
    {
        foreach ($groups as $group) {
            if (!\is_string($group)) {
                throw new \InvalidArgumentException('The group must be a string');
            }
        }

        return [
            'groups' => $groups,
            'skip_null_values' => true,
        ];
    }

    private function serialize(mixed $resource, array $groups, ?string $idCache, ?string $tag): string
    {
        switch (true) {
            case $idCache && $tag:
                try {
                    $jsonResponse = $this->cacheService->getCache($idCache, $resource, $tag, $this->getoptions($groups));
                } catch (\Exception) {
                    throw new BadRequestException('Unable to serialize resource with cache');
                }
                break;
            default:
                try {
                    $jsonResponse = $this->serializer->serialize($resource, 'json', $this->getoptions($groups));
                } catch (\Exception) {
                    throw new BadRequestException('Unable to serialize resource');
                }
                break;
        }

        return $jsonResponse;
    }
}
