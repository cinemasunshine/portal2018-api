<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase as IlluminateRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Log;

/**
 * Refresh database
 *
 * 既存のRefreshDatabaseを参考にLaravel Doctrine ORM用に調整する。
 */
trait RefreshDatabase
{
    use IlluminateRefreshDatabase;

    /**
     * Define hooks to migrate the database before and after each test.
     */
    public function refreshDatabase(): void
    {
        $this->refreshTestDatabase();
    }

    /**
     * Refresh a conventional test database.
     */
    protected function refreshTestDatabase(): void
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('doctrine:schema:drop --force');
            $this->artisan('doctrine:schema:create');

            /**
             * setUp()などで実行するとロールバックされない？
             *
             * @link https://github.com/laravel-doctrine/orm/issues/460
             */
            $this->seed();

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }

    /**
     * Begin a database transaction on the testing database.
     */
    public function beginDatabaseTransaction(): void
    {
        $connection = $this->app->make('em')->getConnection();
        $connection->beginTransaction();
        Log::debug('run beginTransaction()');

        $this->beforeApplicationDestroyed(static function () use ($connection): void {
            $connection->rollBack();
            Log::debug('run rollBack()');
        });
    }
}
