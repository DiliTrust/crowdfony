<?php

declare(strict_types=1);

namespace App\ApiPlatform\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\CrowdfundingCampaign;
use App\Repository\CrowdfundingCampaignRepository;
use Doctrine\ORM\QueryBuilder;

final class FilterDraftingCrowdfundingCampaignExtension implements QueryCollectionExtensionInterface
{
    private CrowdfundingCampaignRepository $repository;

    public function __construct(CrowdfundingCampaignRepository $repository)
    {
        $this->repository = $repository;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null): void
    {
        if ($resourceClass === CrowdfundingCampaign::class) {
            $this->repository->excludeDraftingCampaigns($queryBuilder);
        }
    }
}