<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\AdminUser as BaseAdminUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * AdminUser entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="admin_user", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class AdminUser extends BaseAdminUser
{
}
