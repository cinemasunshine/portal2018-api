<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\TitleRanking as BaseTitleRanking;
use Doctrine\ORM\Mapping as ORM;

/**
 * TitleRanking entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="title_ranking", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TitleRanking extends BaseTitleRanking
{
}
