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
}
