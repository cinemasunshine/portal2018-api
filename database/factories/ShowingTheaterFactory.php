<?php

declare(strict_types=1);

use App\Doctrine\Entities\ShowingTheater;
use Faker\Generator as Faker;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */

$factory->define(ShowingTheater::class, static function (Faker $faker) {
    return [];
});
