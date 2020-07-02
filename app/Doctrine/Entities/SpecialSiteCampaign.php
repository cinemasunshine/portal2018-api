<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\SpecialSiteCampaign as BaseSpecialSiteCampaign;
use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialSiteCampaign entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site_campaign", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSiteCampaign extends BaseSpecialSiteCampaign
{
}
