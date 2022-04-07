<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\CrowdfundingCampaign;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CampaignInvestmentVoter extends Voter
{
    public const INVEST = 'invest';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::INVEST
            && $subject instanceof CrowdfundingCampaign;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (! $user instanceof User) {
            return false;
        }

        \assert($subject instanceof CrowdfundingCampaign);

        if (! $subject->lastInvestedAmount) {
            return false;
        }

        return $subject->isCollectingFunds()
            && $subject->getTotalCollectedFunds()->add($subject->lastInvestedAmount)->lessThanOrEqual($subject->getMaxFundingTarget());
    }
}
