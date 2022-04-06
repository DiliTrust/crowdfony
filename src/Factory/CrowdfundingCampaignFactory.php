<?php

namespace App\Factory;

use App\Entity\CrowdfundingCampaign;
use App\Repository\CrowdfundingCampaignRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<CrowdfundingCampaign>
 *
 * @method static CrowdfundingCampaign|Proxy createOne(array $attributes = [])
 * @method static CrowdfundingCampaign[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static CrowdfundingCampaign|Proxy find(object|array|mixed $criteria)
 * @method static CrowdfundingCampaign|Proxy findOrCreate(array $attributes)
 * @method static CrowdfundingCampaign|Proxy first(string $sortedField = 'id')
 * @method static CrowdfundingCampaign|Proxy last(string $sortedField = 'id')
 * @method static CrowdfundingCampaign|Proxy random(array $attributes = [])
 * @method static CrowdfundingCampaign|Proxy randomOrCreate(array $attributes = [])
 * @method static CrowdfundingCampaign[]|Proxy[] all()
 * @method static CrowdfundingCampaign[]|Proxy[] findBy(array $attributes)
 * @method static CrowdfundingCampaign[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static CrowdfundingCampaign[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CrowdfundingCampaignRepository|RepositoryProxy repository()
 * @method CrowdfundingCampaign|Proxy create(array|callable $attributes = [])
 */
final class CrowdfundingCampaignFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'company' => self::faker()->unique()->company(),
            'project' => self::faker()->boolean(60) ? self::faker()->unique()->sentence(4) : null,
            'currency' => self::faker()->randomElement(['EUR', 'USD', 'CAD', 'GBP']),
            'country' => self::faker()->randomElement(['FR', 'ES', 'IT', 'PT', 'UK', 'US', 'CA']),
            'status' => self::faker()->randomElement([CrowdfundingCampaign::STATUS_DRAFTING, CrowdfundingCampaign::STATUS_OPEN]),
            'activitySector' => ActivitySectorFactory::random(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(CrowdfundingCampaign $crowdfundingCampaign): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CrowdfundingCampaign::class;
    }
}
