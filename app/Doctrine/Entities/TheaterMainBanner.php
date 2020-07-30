<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entities\TheaterMainBanner as BaseTheaterMainBanner;
use Doctrine\ORM\Mapping as ORM;

/**
 * TheaterMainBanner entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater_main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterMainBanner extends BaseTheaterMainBanner
{
}
