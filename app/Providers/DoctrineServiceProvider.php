<?php

namespace App\Providers;

use Doctrine\Common\Cache\WinCacheCache;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ORM\Configuration\Cache\CacheManager;

class DoctrineServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(CacheManager $cacheManager)
    {
        $cacheManager->extend('wincache', function () {
            return new WinCacheCache();
        });
    }
}
