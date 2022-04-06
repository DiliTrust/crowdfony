<?php

declare(strict_types=1);

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * @extends AbstractEnumType<string, string>
 */
final class CampaignStatusType extends AbstractEnumType
{
    public const DRAFTING = 'drafting';
    public const OPEN = 'open';
    public const COLLECTING_FUNDS = 'collecting_funds';
    public const CLOSED = 'closed';

    /**
     * @var array<self::*, string>
     */
    protected static array $choices = [
        self::DRAFTING => 'Drafting',
        self::OPEN => 'Open to public',
        self::COLLECTING_FUNDS => 'Collecting funds',
        self::CLOSED => 'Closed',
    ];
}