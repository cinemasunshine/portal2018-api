<?php

declare(strict_types=1);

use App\Doctrine\Entities\ShowingFormat;
use Faker\Generator as Faker;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */

$factory->define(ShowingFormat::class, static function (Faker $faker) {
    $systems = [
        ShowingFormat::SYSTEM_2D,
        ShowingFormat::SYSTEM_3D,
        ShowingFormat::SYSTEM_4DX,
        ShowingFormat::SYSTEM_4DX3D,
        ShowingFormat::SYSTEM_IMAX,
        ShowingFormat::SYSTEM_IMAX3D,
        ShowingFormat::SYSTEM_SCREENX,
        ShowingFormat::SYSTEM_4DX_SCREEN,
        ShowingFormat::SYSTEM_NONE,
    ];

    $sounds = [
        ShowingFormat::SOUND_BESTIA,
        ShowingFormat::SOUND_DTSX,
        ShowingFormat::SOUND_DOLBY_ATMOS,
        ShowingFormat::SOUND_GDC_IMMERSIVE,
        ShowingFormat::SOUND_NONE,
    ];

    $voices = [
        ShowingFormat::VOICE_SUBTITLE,
        ShowingFormat::VOICE_DUB,
        ShowingFormat::VOICE_NONE,
    ];

    return [
        'system' => $faker->randomElement($systems),
        'sound' => $faker->randomElement($sounds),
        'voice' => $faker->randomElement($voices),
    ];
});
