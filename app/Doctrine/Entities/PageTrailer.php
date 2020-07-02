<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\PageTrailer as BasePageTrailer;
use Doctrine\ORM\Mapping as ORM;

/**
 * PageTrailer entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page_trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class PageTrailer extends BasePageTrailer
{
}
