<?php

namespace Tests\Feature\Api;

use App\Doctrine\Entities\Schedule;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

/**
 * @group feature
 */
class SchedulesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param Schedule $schedule
     * @return \Illuminate\Testing\TestResponse
     */
    private function getShowJson(Schedule $schedule)
    {
        return $this->getJson(sprintf('/schedules/%d', $schedule->getId()));
    }

    /**
     * Test GET /schedules/show
     *
     * まだ実装していないテスト
     * - jsonフォーマット
     * - それぞれの値
     *
     * @test
     * @return void
     */
    public function testShow()
    {
        /** @var Schedule $schedule */
        $schedule = entity(Schedule::class)
            ->states(['before_start'])
            ->create();

        $response = $this->getShowJson($schedule);

        $response->assertOk();
        $response->assertJson([
            'id' => $schedule->getId(),
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function testShowBeforePublicationStart()
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
     * @return void
     */
    public function testShowAfterPublicationEnd()
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
     * @return void
     */
    public function testShowDeleted()
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
     * @return \Illuminate\Testing\TestResponse
     */
    private function getNowShowingJson(array $params = [])
    {
        $url = '/schedules/now-showing';

        if (count($params) > 0) {
            $url .= '?' . http_build_query($params);
        }

        return $this->getJson($url);
    }

    /**
     * @test
     * @return void
     */
    public function testListInvalidTheater()
    {
        // invalid type
        $this->getNowShowingJson(['theater' => ['001']])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['theater' => 'The theater must be a string.']);

        // invalid size
        $this->getNowShowingJson(['theater' => '01'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['theater' => 'The theater must be 3 characters.']);
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
     * @return void
     */
    public function testListNowShowing()
    {
        /** @var \Illuminate\Support\Collection $schedules */
        $schedules = entity(Schedule::class, 5)
            ->states(['after_start'])
            ->create();

        $response = $this->getNowShowingJson();
        $response->assertOk();
        $response->assertJsonCount($schedules->count(), 'schedules');
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
     * @return void
     */
    public function testListComingSoon()
    {
        /** @var \Illuminate\Support\Collection $schedules */
        $schedules = entity(Schedule::class, 5)->states(['before_start'])->create();

        $response = $this->getJson('/schedules/coming-soon');
        $response->assertOk();
        $response->assertJsonCount($schedules->count(), 'schedules');
    }
}
