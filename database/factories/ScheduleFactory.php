<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Doctrine\Entities\Schedule;
use App\Doctrine\Entities\ShowingFormat;
use App\Doctrine\Entities\ShowingTheater;
use App\Doctrine\Entities\Theater;
use App\Doctrine\Entities\Title;
use Faker\Generator as Faker;

$factory->define(Schedule::class, static function (Faker $faker) {
    $startDate = $faker->dateTimeBetween('-2 months', '+2 months');

    $term    = $faker->numberBetween(10, 30);
    $endDate = clone $startDate;
    $endDate->modify(sprintf('+%d days', $term));

    $beforStart    = $faker->numberBetween(3, 7);
    $publicStartDt = clone $startDate;
    $publicStartDt->modify(sprintf('-%d days', $beforStart));

    $publicEndDt = clone $endDate;

    return [
        'title' => static function () {
            return entity(Title::class)->states(['has_image'])->make();
        },
        'startDate' => $startDate,
        'endDate' => $endDate,
        'publicStartDt' => $publicStartDt,
        'publicEndDt' => $publicEndDt,
        'remark' => $faker->realText(),
    ];
});

$factory->state(Schedule::class, 'before_publication_start', static function (Faker $faker) {
    $publicStartDt = $faker->dateTimeBetween('+1 minutes', '+7 days');

    $afterPublicStart = $faker->numberBetween(3, 7);
    $startDate        = clone $publicStartDt;
    $startDate->modify(sprintf('+%d days', $afterPublicStart));

    $term    = $faker->numberBetween(10, 30);
    $endDate = clone $startDate;
    $endDate->modify(sprintf('+%d days', $term));

    $publicEndDt = clone $endDate;

    return [
        'startDate' => $startDate,
        'endDate' => $endDate,
        'publicStartDt' => $publicStartDt,
        'publicEndDt' => $publicEndDt,
    ];
});

$factory->state(Schedule::class, 'after_publication_end', static function (Faker $faker) {
    $publicEndDt = $faker->dateTimeBetween('-7 days', '-1 minutes');

    $term      = $faker->numberBetween(10, 30);
    $startDate = clone $publicEndDt;
    $startDate->modify(sprintf('-%d days', $term));

    $beforeStart   = $faker->numberBetween(3, 7);
    $publicStartDt = clone $startDate;
    $publicStartDt->modify(sprintf('-%d days', $beforeStart));

    $endDate = clone $publicEndDt;

    return [
        'startDate' => $startDate,
        'endDate' => $endDate,
        'publicStartDt' => $publicStartDt,
        'publicEndDt' => $publicEndDt,
    ];
});

$factory->state(Schedule::class, 'before_start', static function (Faker $faker) {
    $startDate = $faker->dateTimeBetween('+1 days', '+7 days');

    $term    = $faker->numberBetween(10, 30);
    $endDate = clone $startDate;
    $endDate->modify(sprintf('+%d days', $term));

    $publicStartDt = $faker->dateTimeBetween('-3 days', 'now');

    $publicEndDt = clone $endDate;

    return [
        'startDate' => $startDate,
        'endDate' => $endDate,
        'publicStartDt' => $publicStartDt,
        'publicEndDt' => $publicEndDt,
    ];
});

$factory->state(Schedule::class, 'after_start', static function (Faker $faker) {
    $startDate = $faker->dateTimeBetween('-7 days', 'now');

    $term    = $faker->numberBetween(10, 30);
    $endDate = clone $startDate;
    $endDate->modify(sprintf('+%d days', $term));

    $beforeStart   = $faker->numberBetween(3, 7);
    $publicStartDt = clone $startDate;
    $publicStartDt->modify(sprintf('-%d days', $beforeStart));

    $publicEndDt = clone $endDate;

    return [
        'startDate' => $startDate,
        'endDate' => $endDate,
        'publicStartDt' => $publicStartDt,
        'publicEndDt' => $publicEndDt,
    ];
});

$factory->afterCreating(Schedule::class, static function (Schedule $schedule, Faker $faker) {
    $createShowingFormats = static function (Schedule $schedule, $count) {
        $entities = entity(ShowingFormat::class, $count)->create(['schedule' => $schedule]);

        if ($entities instanceof \Illuminate\Support\Collection) {
            $entities = $entities->all();
        } else {
            $entities = [$entities];
        }

        return $entities;
    };

    $showingFormats = $createShowingFormats($schedule, $faker->numberBetween(1, 3));

    foreach ($showingFormats as $showingFormat) {
        $schedule->getShowingFormats()->add($showingFormat);
    }

    $createShowingTheaters = static function (Schedule $schedule) use ($faker) {
        $em = app('em');

        $allTheaters = $em->getRepository(Theater::class)->findAll();
        $count       = $faker->numberBetween(1, count($allTheaters));

        $theaters = $faker->unique(true)->randomElements($allTheaters, $count);

        $entities = [];

        foreach ($theaters as $theater) {
            $entities[] = entity(ShowingTheater::class)->create([
                'schedule' => $schedule,
                'theater' => $theater,
            ]);
        }

        return $entities;
    };

    $showingTheaters = $createShowingTheaters($schedule);

    foreach ($showingTheaters as $showingTheater) {
        $schedule->getShowingTheaters()->add($showingTheater);
    }
});
