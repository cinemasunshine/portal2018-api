<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\PageCampaign as BasePageCampaign;
use Doctrine\ORM\Mapping as ORM;

/**
 * PageCampaign entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page_campaign", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class PageCampaign extends BasePageCampaign
{
}
