<?php

namespace App\Http\Controllers;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;

class DoctrineController extends Controller
{
    /**
     * @param EntityManagerInterface $em
     * @return void
     */
    public function cacheStats(EntityManagerInterface $em)
    {
        $queryCacheDriver = $em->getConfiguration()->getQueryCacheImpl();

        if ($queryCacheDriver) {
            $this->dumpCacheStats($queryCacheDriver, 'Query');
        }

        $metadataCacheDriver = $em->getConfiguration()->getMetadataCacheImpl();

        if ($metadataCacheDriver) {
            $this->dumpCacheStats($metadataCacheDriver, 'Metadata');
        }
    }

    /**
     * @param Cache $cacheDriver
     * @param string $target
     * @return void
     */
    protected function dumpCacheStats(Cache $cacheDriver, string $target)
    {
        echo sprintf('%s cache', $target);
        echo '<br>';
        echo 'driver: ' . get_class($cacheDriver);
        dump($cacheDriver->getStats());
    }

    /**
     * Cache clear
     *
     * Webからキャッシュをクリアする機能を提供する。
     * Azure(Windows)で使用するWinCacheはWebとCLIが別になっていて、コンソールからはクリアできないらしい。
     * よってその代替として実装。
     *
     * @param Request $request
     * @param string $target
     * @param EntityManagerInterface $em
     * @return void
     */
    public function cacheClear(Request $request, string $target, EntityManagerInterface $em)
    {
        if ($target === 'query') {
            $cacheDriver = $em->getConfiguration()->getQueryCacheImpl();
        } elseif ($target === 'metadata') {
            $cacheDriver = $em->getConfiguration()->getMetadataCacheImpl();
        } else {
            throw new \InvalidArgumentException('Invalid "target".');
        }

        $flush = $request->query('flush') === 'true';

        return $this->doCacheClear($cacheDriver, $flush);
    }

    /**
     * @param CacheProvider|null $cacheDriver
     * @param boolean $flush
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @see \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand::execute()
     * @see \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand::execute()
     */
    protected function doCacheClear(?CacheProvider $cacheDriver, bool $flush = false): string
    {
        if (!$cacheDriver) {
            throw new \InvalidArgumentException('No Query cache driver is configured on given EntityManager.');
        }

        $result  = $cacheDriver->deleteAll();
        $message = ($result) ? 'Successfully deleted cache entries.' : 'No cache entries were deleted.';

        if ($flush) {
            $result  = $cacheDriver->flushAll();
            $message = ($result) ? 'Successfully flushed cache entries.' : $message;
        }

        if (!$result) {
            throw new \RuntimeException($message);
        }

        return $message;
    }
}
