<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\OyakoCinemaTitle as BaseOyakoCinemaTitle;
use Doctrine\ORM\Mapping as ORM;

/**
 * OyakoCinemaTitle entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="oyako_cinema_title", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class OyakoCinemaTitle extends BaseOyakoCinemaTitle
{
}
