<?php

/** @var LaravelDoctrine\ORM\Testing\Factory $factory */

use App\Doctrine\Entities\File;
use Faker\Generator as Faker;

$factory->define(File::class, static function (Faker $faker) {
    return [
        'size' => $faker->numberBetween(10000, 1000000),
    ];
});

$factory->state(File::class, 'image', static function (Faker $faker) {
    $extension = 'jpg';

    return [
        'name' => $faker->unique()->md5 . '.' . $extension,
        'originalName' => 'example.' . $extension,
        'mimeType' => 'image/jpeg',
    ];
});
