<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\TheaterCampaign as BaseTheaterCampaign;
use Doctrine\ORM\Mapping as ORM;

/**
 * TheaterCampaign entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater_campaign", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterCampaign extends BaseTheaterCampaign
{
}
