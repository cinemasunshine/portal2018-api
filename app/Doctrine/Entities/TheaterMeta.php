<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\TheaterMeta as BaseTheaterMeta;
use Doctrine\ORM\Mapping as ORM;

/**
 * TheaterMeta entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater_meta", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterMeta extends BaseTheaterMeta
{
}

