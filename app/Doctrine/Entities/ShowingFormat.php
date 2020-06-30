<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\ShowingFormat as BaseShowingFormat;
use Doctrine\ORM\Mapping as ORM;

/**
 * ShowingFormat entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="showing_format", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class ShowingFormat extends BaseShowingFormat
{
}
