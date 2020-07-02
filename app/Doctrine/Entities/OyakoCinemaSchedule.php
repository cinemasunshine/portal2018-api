<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\OyakoCinemaSchedule as BaseOyakoCinemaSchedule;
use Doctrine\ORM\Mapping as ORM;

/**
 * OyakoCinemaSchedule entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="oyako_cinema_schedule", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class OyakoCinemaSchedule extends BaseOyakoCinemaSchedule
{
}
