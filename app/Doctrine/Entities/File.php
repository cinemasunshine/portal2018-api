<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entities\File as BaseFile;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Return URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        return Storage::disk('azure-blob-file')->url($this->getName());
    }
}
