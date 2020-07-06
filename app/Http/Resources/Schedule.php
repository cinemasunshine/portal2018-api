<?php

namespace App\Http\Resources;

use App\Doctrine\Entities\Schedule as ScheduleEntity;
use Illuminate\Http\Resources\Json\JsonResource;

class Schedule extends JsonResource
{
    /**
     * @param ScheduleEntity $schedule
     */
    public function __construct(ScheduleEntity $schedule)
    {
        parent::__construct($schedule);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array{
     *     id: int,
     *     start_date: string,
     *     end_date: string,
     *     remark: ?string,
     *     title: \App\Http\Resources\Title,
     *     formats: \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * }
     */
    public function toArray($request)
    {
        /** @var ScheduleEntity $schedule */
        $schedule = $this->resource;

        return [
            'id' => $schedule->getId(),
            'start_date' => $schedule->getStartDate()->format('Y-m-d'),
            'end_date' => $schedule->getEndDate()->format('Y-m-d'),
            'remark' => $schedule->getRemark(),
            'title' => new Title($schedule->getTitle()),
            'formats' => ShowingFormat::collection($schedule->getShowingFormats()->toArray()),
        ];
    }
}
