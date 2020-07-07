<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ScheduleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array{
     *     schedules: \Illuminate\Support\Collection
     * }
     */
    public function toArray($request)
    {
        return [
            'schedules' => $this->collection,
        ];
    }
}
