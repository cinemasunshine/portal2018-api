<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\News as BaseNews;
use Doctrine\ORM\Mapping as ORM;

/**
 * News entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="news", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class News extends BaseNews
{
}
