<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\Schedule as BaseSchedule;
use Doctrine\ORM\Mapping as ORM;

/**
 * Schedule entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="schedule", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Schedule extends BaseSchedule
{
}
