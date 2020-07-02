<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\File as BaseFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * File entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="file", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class File extends BaseFile
{
    /** @var string */
    protected static $blobContainer = 'file';

    /**
     * get blob container
     *
     * @return string
     */
    public static function getBlobContainer(): string
    {
        return self::$blobContainer;
    }
}
