<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * @group feature
 */
class SchedulesTest extends TestCase
{
    /**
     * @param mixed $theater
     */
    private function getListJson($theater): TestResponse
    {
        $url = '/schedules/now-showing';

        if ($theater) {
            $url .= '?' . http_build_query(['theater' => $theater]);
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
        $this->getListJson(['theater' => $theater])
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
     * - 必要なフィールドが含まれているか
     *
     * @test
     */
    public function testListNowShowing(): void
    {
        $response = $this->getJson('/schedules/now-showing?theater=120');

        $response->assertOk();
        $response->assertJsonCount(1, 'schedules');
    }

    /**
     * Test GET /schedules/comming-soon
     *
     * まだ実装していないテスト
     * - 必要なフィールドが含まれているか
     *
     * @test
     */
    public function testListCommingSoon(): void
    {
        $response = $this->getJson('/schedules/comming-soon?theater=120');

        $response->assertOk();
        $response->assertJsonCount(1, 'schedules');
    }

    /**
     * comming-soonのtypo
     *
     * @test
     */
    public function testListComingSoon(): void
    {
        $response = $this->getJson('/schedules/coming-soon?theater=120');

        $response->assertOk();
        $response->assertJsonCount(1, 'schedules');
    }
}
