<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\Title as BaseTitle;
use Doctrine\ORM\Mapping as ORM;

/**
 * Title entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="title", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Title extends BaseTitle
{
}
