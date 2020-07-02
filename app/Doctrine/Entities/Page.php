<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\Page as BasePage;
use Doctrine\ORM\Mapping as ORM;

/**
 * Page entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Page extends BasePage
{
}
