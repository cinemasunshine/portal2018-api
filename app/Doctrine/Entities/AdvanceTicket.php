<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;;

use Cinemasunshine\ORM\Entity\AdvanceTicket as BaseAdvanceTicket;
use Doctrine\ORM\Mapping as ORM;

/**
 * AdvanceTicket entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="advance_ticket", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class AdvanceTicket extends BaseAdvanceTicket
{
}
