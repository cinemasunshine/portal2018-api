<?php

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
     *
     * @return void
     */
    public function refreshDatabase()
    {
        $this->refreshTestDatabase();
    }

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('doctrine:schema:drop --force');
            $this->artisan('doctrine:schema:create');

            /**
             * setUp()などで実行するとロールバックされない？
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
     *
     * @return void
     */
    public function beginDatabaseTransaction()
    {
        $connection = $this->app->make('em')->getConnection();
        $connection->beginTransaction();
        Log::debug('run beginTransaction()');

        $this->beforeApplicationDestroyed(function () use ($connection) {
            $connection->rollBack();
            Log::debug('run rollBack()');
        });
    }
}
