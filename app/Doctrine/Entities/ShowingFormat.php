<?php

declare(strict_types=1);

namespace App\Doctrine\Entities;

use Cinemasunshine\ORM\Entity\ShowingFormat as BaseShowingFormat;
use Doctrine\ORM\Mapping as ORM;

/**
 * ShowingFormat entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="showing_format", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class ShowingFormat extends BaseShowingFormat
{
    /** @var array<int, string> */
    protected static $systemLables = [
        self::SYSTEM_2D         => '2D',
        self::SYSTEM_3D         => '3D',
        self::SYSTEM_4DX        => '4DX',
        self::SYSTEM_4DX3D      => '4DX3D',
        self::SYSTEM_IMAX       => 'IMAX',
        self::SYSTEM_IMAX3D     => 'IMAX3D',
        self::SYSTEM_SCREENX    => 'ScreenX', // SASAKI-351
        self::SYSTEM_4DX_SCREEN => '4DX SCREEN', // SASAKI-428、SASAKI-525
        self::SYSTEM_NONE       => 'なし',
    ];

    /** @var array<int, string> */
    protected static $soundLabels = [
        self::SOUND_BESTIA        => 'BESTIA',
        self::SOUND_DTSX          => 'dts-X',
        self::SOUND_DOLBY_ATMOS   => 'dolbyatmos',
        self::SOUND_GDC_IMMERSIVE => 'GDCイマーシブサウンド',
        self::SOUND_NONE          => 'なし',
    ];

    /** @var array<int, string> */
    protected static $voiceLabels = [
        self::VOICE_SUBTITLE => '字幕',
        self::VOICE_DUB      => '吹替',
        self::VOICE_NONE     => 'なし', // SASAKI-297
    ];

    /**
     * Return system text
     *
     * @return string
     */
    public function getSystemText(): string
    {
        return self::$systemLables[$this->getSystem()];
    }

    /**
     * Return sound text
     *
     * @return string
     */
    public function getSoundText(): string
    {
        return self::$soundLabels[$this->getSound()];
    }

    /**
     * Return voice text
     *
     * @return string
     */
    public function getVoiceText(): string
    {
        return self::$voiceLabels[$this->getVoice()];
    }
}
