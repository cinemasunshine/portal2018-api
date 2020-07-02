<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\SpecialSiteTrailer as BaseSpecialSiteTrailer;
use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialSiteTrailer entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site_trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSiteTrailer extends BaseSpecialSiteTrailer
{
}
