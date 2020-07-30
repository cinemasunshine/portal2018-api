<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entities\PageNews as BasePageNews;
use Doctrine\ORM\Mapping as ORM;

/**
 * PageNews entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page_news", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class PageNews extends BasePageNews
{
}
