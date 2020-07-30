<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entities\Trailer as BaseTrailer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trailer entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Trailer extends BaseTrailer
{
}
