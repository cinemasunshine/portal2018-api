<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use InvalidArgumentException;
use RuntimeException;

/**
 * Doctrine controller
 *
 * 主にWebからキャッシュ操作する機能を提供する。
 * 元々はAzure (Windows)＋スワップ運用のための機能。
 * GCP環境で必要なのかは不明。
 */
class DoctrineController extends Controller
{
    public function cacheStats(EntityManagerInterface $em): void
    {
        $queryCacheDriver = $em->getConfiguration()->getQueryCacheImpl();

        if ($queryCacheDriver) {
            $this->dumpCacheStats($queryCacheDriver, 'Query');
        }

        $metadataCacheDriver = $em->getConfiguration()->getMetadataCacheImpl();

        if (! $metadataCacheDriver) {
            return;
        }

        $this->dumpCacheStats($metadataCacheDriver, 'Metadata');
    }

    protected function dumpCacheStats(Cache $cacheDriver, string $target): void
    {
        echo sprintf('%s cache', $target);
        echo '<br>';
        echo 'driver: ' . get_class($cacheDriver);
        dump($cacheDriver->getStats());
    }

    public function cacheClear(Request $request, string $target, EntityManagerInterface $em): string
    {
        if ($target === 'query') {
            $cacheDriver = $em->getConfiguration()->getQueryCacheImpl();
        } elseif ($target === 'metadata') {
            $cacheDriver = $em->getConfiguration()->getMetadataCacheImpl();
        } else {
            throw new InvalidArgumentException('Invalid "target".');
        }

        if (! $cacheDriver) {
            throw new InvalidArgumentException('No cache driver is configured on given EntityManager.');
        }

        if (! $cacheDriver instanceof CacheProvider) {
            throw new InvalidArgumentException('This cache driver does not support clear.');
        }

        $flush = $request->query('flush') === 'true';

        return $this->doCacheClear($cacheDriver, $flush);
    }

    /**
     * @see \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand::execute()
     * @see \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand::execute()
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    protected function doCacheClear(CacheProvider $cacheDriver, bool $flush = false): string
    {
        $result  = $cacheDriver->deleteAll();
        $message = $result ? 'Successfully deleted cache entries.' : 'No cache entries were deleted.';

        if ($flush) {
            $result  = $cacheDriver->flushAll();
            $message = $result ? 'Successfully flushed cache entries.' : $message;
        }

        if (! $result) {
            throw new RuntimeException($message);
        }

        return $message;
    }
}
