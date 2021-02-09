<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Doctrine\Entities\Schedule as ScheduleEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class Schedule extends JsonResource
{
    public function __construct(ScheduleEntity $schedule)
    {
        parent::__construct($schedule);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array{
     *     id: int,
     *     start_date: string,
     *     end_date: string,
     *     remark: ?string,
     *     title: Title,
     *     formats: AnonymousResourceCollection,
     *     theaters: AnonymousResourceCollection,
     * }
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function toArray($request): array
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
            'theaters' => ShowingTheater::collection($schedule->getShowingTheaters()->toArray()),
        ];
    }
}
