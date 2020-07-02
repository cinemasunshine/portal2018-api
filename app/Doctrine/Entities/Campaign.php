<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\Campaign as BaseCampaign;
use Doctrine\ORM\Mapping as ORM;

/**
 * Campaign entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="campaign", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Campaign extends BaseCampaign
{
}
