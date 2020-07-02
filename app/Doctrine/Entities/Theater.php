<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\Theater as BaseTheater;
use Doctrine\ORM\Mapping as ORM;

/**
 * Theater entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Theater extends BaseTheater
{
}
