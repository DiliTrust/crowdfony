<?php

namespace App\Repository;

use App\DBAL\Types\CampaignStatusType;
use App\Entity\CrowdfundingCampaign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CrowdfundingCampaign>
 *
 * @method CrowdfundingCampaign|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrowdfundingCampaign|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrowdfundingCampaign[]    findAll()
 * @method CrowdfundingCampaign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrowdfundingCampaignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrowdfundingCampaign::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CrowdfundingCampaign $entity, bool $flush = true): void
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
    public function remove(CrowdfundingCampaign $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function excludeDraftingCampaigns(QueryBuilder $queryBuilder): void
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere($queryBuilder->expr()->neq($rootAlias . '.status', ':drafting'))
            ->setParameter('drafting', CampaignStatusType::DRAFTING);
    }
}
