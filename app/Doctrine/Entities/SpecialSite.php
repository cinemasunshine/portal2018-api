<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\SpecialSite as BaseSpecialSite;
use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialSite entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSite extends BaseSpecialSite
{
}
