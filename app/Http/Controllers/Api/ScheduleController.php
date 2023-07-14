<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Request as Http7Request;
use Illuminate\Http\Request;
use Psr\Http\Client\ClientInterface;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $type route parameter
     * @return array{schedules:array<string,mixed>}
     */
    public function index(Request $request, string $type, ClientInterface $client): array
    {
        // fix typo
        $type = $type === 'coming-soon' ? 'comming-soon' : $type;

        $request->validate([
            'theater' => ['required', 'string', 'size:3'],
        ]);

        /** @var string $theaterCode */
        $theaterCode = $request->query('theater', '');

        $response = $client->sendRequest(
            new Http7Request('GET', $this->getRequestUrl($type, $theaterCode))
        );
        $contents = $response->getBody()->getContents();

        return ['schedules' => json_decode($contents)->schedules];
    }

    private function getRequestUrl(string $type, string $theaterCode): string
    {
        return sprintf('%s/schedule/%s/%s.json', config('services.schedule.base_url'), $type, $theaterCode);
    }
}
