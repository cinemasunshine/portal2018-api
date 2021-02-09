<?php

declare(strict_types=1);

use App\Doctrine\Entities\File;
use App\Doctrine\Entities\Title;
use Faker\Generator as Faker;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */

$factory->define(Title::class, static function (Faker $faker) {
    $casts = [];

    for ($i = rand(0, 5); $i > 0; $i--) {
        $casts[] = $faker->name;
    }

    $publishingExpectedDate = $faker->optional(0.5)->dateTimeBetween('now', '+ 2 months');

    $officialSite = $faker->optional(0.5, '')->url;

    return [
        'image' => null,
        'name' => $faker->title,
        'nameKana' => '',
        'nameOriginal' => '',
        'credit' => '',
        'catchcopy' => $faker->realText(50),
        'introduction' => $faker->realText(),
        'director' => $faker->name,
        'cast' => implode('、', $casts),
        'publishingExpectedDate' => $publishingExpectedDate,
        'officialSite' => $officialSite,
        'rating' => $faker->numberBetween(1, 4),
        'universal' => $faker->randomElements(['1', '2'], $faker->numberBetween(0, 2)),
    ];
});

$factory->state(Title::class, 'has_image', static function (Faker $faker) {
    return [
        'image' => static function () {
            return entity(File::class)->states(['image'])->make();
        },
    ];
});
