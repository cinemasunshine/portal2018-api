<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Doctrine\Entities\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

/**
 * @group feature
 */
class SchedulesTest extends TestCase
{
    use RefreshDatabase;

    private function getShowJson(Schedule $schedule): TestResponse
    {
        return $this->getJson(sprintf('/schedules/%d', $schedule->getId()));
    }

    /**
     * @test
     */
    public function testShowBeforePublicationStart(): void
    {
        /** @var Schedule $schedule */
        $schedule = entity(Schedule::class)
            ->states(['before_publication_start'])
            ->create();

        $response = $this->getShowJson($schedule);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function testShowAfterPublicationEnd(): void
    {
        /** @var Schedule $schedule */
        $schedule = entity(Schedule::class)
            ->states(['after_publication_end'])
            ->create();

        $response = $this->getShowJson($schedule);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function testShowDeleted(): void
    {
        /** @var Schedule $schedule */
        $schedule = entity(Schedule::class)
            ->states(['before_start'])
            ->create(['isDeleted' => true]);

        $response = $this->getShowJson($schedule);

        $response->assertNotFound();
    }

    /**
     * @param array<string, mixed> $params
     */
    private function getNowShowingJson(array $params = []): TestResponse
    {
        $url = '/schedules/now-showing';

        if (count($params) > 0) {
            $url .= '?' . http_build_query($params);
        }

        return $this->getJson($url);
    }

    /**
     * @dataProvider listInvalidTheaterDataProvider
     * @test
     *
     * @param mixed $theater
     */
    public function testListInvalidTheater($theater, string $error): void
    {
        $this->getNowShowingJson(['theater' => $theater])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['theater' => $error]);
    }

    /**
     * @return array<string,array{mixed,string}>
     */
    public function listInvalidTheaterDataProvider(): array
    {
        return [
            'invalid type' => [['001'], 'The theater must be a string.'],
            'invalid size' => ['01', 'The theater must be 3 characters.'],
            'require' => [null, 'The theater field is required.'],
        ];
    }

    /**
     * Test GET /schedules/now-showing
     *
     * まだ実装していないテスト
     * - jsonフォーマット
     * - それぞれの値
     * - 有効なScheduleが全て含まれること
     * - 無効なScheduleは含まれないこと
     * - 並び順
     *
     * @test
     */
    public function testListNowShowing(): void
    {
        /** @var Collection $schedules */
        $schedules = entity(Schedule::class, 5)
            ->states(['after_start'])
            ->create();

        $gdcsSchedules = $schedules->filter(static function ($schedule) {
            foreach ($schedule->getShowingTheaters() as $showingTheater) {
                if ($showingTheater->getTheater()->getMasterCode() === '120') {
                    return true;
                }
            }

            return false;
        });

        $response = $this->getNowShowingJson(['theater' => '120']);
        $response->assertOk();
        $response->assertJsonCount($gdcsSchedules->count(), 'schedules');
    }

    /**
     * Test GET /schedules/coming-soon
     *
     * まだ実装していないテスト
     * - jsonフォーマット
     * - それぞれの値
     * - 有効なScheduleのみ含まれること
     * - 無効なScheduleは含まれないこと
     * - 並び順
     *
     * @test
     */
    public function testListComingSoon(): void
    {
        /** @var Collection $schedules */
        $schedules = entity(Schedule::class, 5)->states(['before_start'])->create();

        $gdcsSchedules = $schedules->filter(static function ($schedule) {
            foreach ($schedule->getShowingTheaters() as $showingTheater) {
                if ($showingTheater->getTheater()->getMasterCode() === '120') {
                    return true;
                }
            }

            return false;
        });

        $response = $this->getJson('/schedules/coming-soon?theater=120');
        $response->assertOk();
        $response->assertJsonCount($gdcsSchedules->count(), 'schedules');
    }
}
