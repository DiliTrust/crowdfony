<?php

namespace App\Repository;

use App\Entity\ActivitySector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivitySector>
 *
 * @method ActivitySector|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivitySector|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivitySector[]    findAll()
 * @method ActivitySector[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivitySectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivitySector::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ActivitySector $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ActivitySector $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return ActivitySector[]
     */
    public function findActiveSectors(): array
    {
        return $this->findBy(['isEnabled' => true], ['name' => 'ASC']);
    }

    public function findActiveSector(int $id): ?ActivitySector
    {
        return $this->findOneBy(['id' => $id, 'isEnabled' => true]);
    }

    public function addEnabledSectorCriteria(QueryBuilder $queryBuilder): void
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq($rootAlias . '.isEnabled', ':isEnabled'))
            ->setParameter('isEnabled', true);
    }
}
