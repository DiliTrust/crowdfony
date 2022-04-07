<?php

declare(strict_types=1);

namespace App\ApiPlatform\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\ActivitySector;
use App\Entity\User;
use App\Repository\ActivitySectorRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class FilterDisabledActivitySectorExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private ActivitySectorRepository $repository;
    private Security $security;

    public function __construct(ActivitySectorRepository $repository, Security $security)
    {
        $this->repository = $repository;
        $this->security   = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null): void
    {
        if ($this->security->isGranted(User::ADMIN)) {
            return;
        }

        if ($resourceClass === ActivitySector::class) {
            $this->repository->addEnabledSectorCriteria($queryBuilder);
        }
    }

    /**
     * @param string[]             $identifiers
     * @param array<string, mixed> $context
     */
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = []): void
    {
        if ($this->security->isGranted(User::ADMIN)) {
            return;
        }

        if ($resourceClass === ActivitySector::class) {
            $this->repository->addEnabledSectorCriteria($queryBuilder);
        }
    }
}