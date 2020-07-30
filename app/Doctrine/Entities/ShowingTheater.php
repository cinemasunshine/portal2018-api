<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entities\ShowingTheater as BaseShowingTheater;
use Doctrine\ORM\Mapping as ORM;

/**
 * ShowingTheater entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="showing_theater", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class ShowingTheater extends BaseShowingTheater
{
}
