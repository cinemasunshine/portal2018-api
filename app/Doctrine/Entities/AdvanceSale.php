<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\AdvanceSale as BaseAdvanceSale;
use Doctrine\ORM\Mapping as ORM;

/**
 * AdvanceSale entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="advance_sale", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class AdvanceSale extends BaseAdvanceSale
{
}
