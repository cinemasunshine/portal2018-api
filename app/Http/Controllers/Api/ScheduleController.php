<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Doctrine\Entities\Schedule;
use App\Http\Controllers\Controller;
use App\Http\Resources\Schedule as ScheduleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //
    // }

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
