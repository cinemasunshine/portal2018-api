<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\TheaterTrailer as BaseTheaterTrailer;
use Doctrine\ORM\Mapping as ORM;

/**
 * TheaterTrailer entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater_trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterTrailer extends BaseTheaterTrailer
{
}
