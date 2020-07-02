<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\SpecialSiteMainBanner as BaseSpecialSiteMainBanner;
use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialSiteMainBanner entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site_main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSiteMainBanner extends BaseSpecialSiteMainBanner
{
}
