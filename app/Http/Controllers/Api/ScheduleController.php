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
use Illuminate\Http\Response;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $type route parameter
     * @return ScheduleCollectionResource<ScheduleResource>
     */
    public function index(Request $request, string $type, EntityManagerInterface $em): ScheduleCollectionResource
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
     * @return Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule): JsonResource
    {
        return new ScheduleResource($schedule);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Schedule  $schedule
     * @return Response
     */
    // public function edit(Schedule $schedule)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  Schedule  $schedule
     * @return Response
     */
    // public function update(Request $request, Schedule $schedule)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Schedule  $schedule
     * @return Response
     */
    // public function destroy(Schedule $schedule)
    // {
    //     //
    // }
}
