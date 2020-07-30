<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entities\PageMainBanner as BasePageMainBanner;
use Doctrine\ORM\Mapping as ORM;

/**
 * PageMainBanner entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page_main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class PageMainBanner extends BasePageMainBanner
{
}
