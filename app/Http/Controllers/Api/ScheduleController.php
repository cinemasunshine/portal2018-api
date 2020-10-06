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
    /**
     * Display a listing of the resource.
     *
     * @param Request                $request
     * @param string                 $type    route parameter
     * @param EntityManagerInterface $em
     * @return ScheduleCollectionResource<ScheduleResource>
     */
    public function index(Request $request, string $type, EntityManagerInterface $em)
    {
        $request->validate([
            'theater' => ['string', 'size:3'],
        ]);

        /** @var string|null $theater */
        $theater = $request->query('theater');

        /** @var ScheduleRepository $repository */
        $repository = $em->getRepository(Schedule::class);
        $schedules  = $repository->findPublic($type, $theater);

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
     * @param  Schedule $schedule
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
