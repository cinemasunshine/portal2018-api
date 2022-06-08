<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entities\Title as BaseTitle;
use Doctrine\ORM\Mapping as ORM;

/**
 * Title entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="title", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 *
 * @method File|null getImage()
 */
class Title extends BaseTitle
{
    /** @var array<int, string> */
    protected static array $ratingLabels = [
        1 => 'G',
        2 => 'PG12',
        3 => 'R15+',
        4 => 'R18+',
    ];

    /** @var array<int, string> */
    protected static array $universalLabels = [
        1 => '音声上映',
        2 => '字幕上映',
    ];

    public function getRatingText(): ?string
    {
        return self::$ratingLabels[$this->getRating()] ?? null;
    }

    /**
     * @return string[]
     */
    public function getUniversalTexts(): array
    {
        $univarsal = $this->getUniversal() ?? [];
        $labels    = self::$universalLabels;

        return array_map(static function ($value) use ($labels) {
            return $labels[$value];
        }, $univarsal);
    }
}
