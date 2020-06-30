<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\TheaterNews as BaseTheaterNews;
use Doctrine\ORM\Mapping as ORM;

/**
 * TheaterNews entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater_news", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterNews extends BaseTheaterNews
{
}
