<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Doctrine\Entities\Schedule;
use App\Doctrine\Repositories\ScheduleRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\Schedule as ScheduleResource;
use App\Http\Resources\ScheduleCollection as ScheduleCollectionResource;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleController extends Controller
{
    protected const LIST_TYPE_NOW_SHOWING = 'now-showing';
    protected const LIST_TYPE_COMING_SOON = 'coming-soon';

    /**
     * Display a listing of the resource.
     *
     * @param string $type
     * @return ScheduleCollectionResource<ScheduleResource>
     * @throws \InvalidArgumentException
     */
    public function index(string $type, EntityManagerInterface $em)
    {
        /** @var ScheduleRepository $repository */
        $repository = $em->getRepository(Schedule::class);
        $schedules = [];

        if ($type === self::LIST_TYPE_NOW_SHOWING) {
            $schedules = $repository->findNowShowing();
        } elseif ($type === self::LIST_TYPE_COMING_SOON) {
            $schedules = $repository->findComingSoon();
        } else {
            throw new \InvalidArgumentException('Invalid "type".');
        }

        return new ScheduleCollectionResource($schedules);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  Schedule  $schedule
     * @return JsonResource
     */
    public function show(Schedule $schedule)
    {
        return new ScheduleResource($schedule);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    // public function edit(Schedule $schedule)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Schedule $schedule)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Schedule $schedule)
    // {
    //     //
    // }
}
