<?php

namespace App\Factory;

use App\Entity\ActivitySector;
use App\Repository\ActivitySectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<ActivitySector>
 *
 * @method static ActivitySector|Proxy createOne(array $attributes = [])
 * @method static ActivitySector[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static ActivitySector|Proxy find(object|array|mixed $criteria)
 * @method static ActivitySector|Proxy findOrCreate(array $attributes)
 * @method static ActivitySector|Proxy first(string $sortedField = 'id')
 * @method static ActivitySector|Proxy last(string $sortedField = 'id')
 * @method static ActivitySector|Proxy random(array $attributes = [])
 * @method static ActivitySector|Proxy randomOrCreate(array $attributes = [])
 * @method static ActivitySector[]|Proxy[] all()
 * @method static ActivitySector[]|Proxy[] findBy(array $attributes)
 * @method static ActivitySector[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static ActivitySector[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ActivitySectorRepository|RepositoryProxy repository()
 * @method ActivitySector|Proxy create(array|callable $attributes = [])
 */
final class ActivitySectorFactory extends ModelFactory
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
            'name' => self::faker()->unique()->sentence(self::faker()->numberBetween(2, 3)),
            'description' => self::faker()->paragraphs(self::faker()->numberBetween(2, 12), true),
            'isEnabled' => self::faker()->boolean(82),
            'campaigns' => new ArrayCollection(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(ActivitySector $activitySector): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ActivitySector::class;
    }
}
