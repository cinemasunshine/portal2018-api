<?php

declare(strict_types=1);

namespace App\Doctrine\Repositories;

use App\Doctrine\Entities\Schedule;
use App\Doctrine\Entities\ShowingTheater;
use Cinemasunshine\ORM\Repositories\ScheduleRepository as BaseRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use InvalidArgumentException;

class ScheduleRepository extends BaseRepository
{
    public const PUBLIC_TYPE_NOW_SHOWING = 'now-showing';
    public const PUBLIC_TYPE_COMING_SOON = 'coming-soon';

    /**
     * @return Schedule[]
     */
    public function findPublic(string $type, ?string $theater = null): array
    {
        if (
            ! in_array($type, [
                self::PUBLIC_TYPE_NOW_SHOWING,
                self::PUBLIC_TYPE_COMING_SOON,
            ])
        ) {
            throw new InvalidArgumentException('Invalid "type".');
        }

        $alias = 's';
        $qb    = $this->createQueryBuilder($alias);

        $aliasTitle      = 't';
        $aliasTitleImage = 'ti';
        $qb
            ->addSelect($aliasTitle)
            ->innerJoin(sprintf('%s.title', $alias), $aliasTitle)
            ->addSelect($aliasTitleImage)
            ->leftJoin(sprintf('%s.image', $aliasTitle), $aliasTitleImage);

        $aliasShowingFormats = 'sf';
        $qb
            ->addSelect($aliasShowingFormats)
            ->innerJoin(sprintf('%s.showingFormats', $alias), $aliasShowingFormats);

        $aliasShowingTheaters1 = 'st1';
        $qb
            ->addSelect($aliasShowingTheaters1)
            ->innerJoin(sprintf('%s.showingTheaters', $alias), $aliasShowingTheaters1);

        if ($type === self::PUBLIC_TYPE_NOW_SHOWING) {
            $this->addNowShowingQuery($qb, $alias);
        } elseif ($type === self::PUBLIC_TYPE_COMING_SOON) {
            $this->addComingSoonQuery($qb, $alias);
        }

        if ($theater) {
            $aliasShowingTheaters2 = 'st2';
            $aliasTheater          = 'th';
            $qb
                ->innerJoin(sprintf('%s.showingTheaters', $alias), $aliasShowingTheaters2)
                ->innerJoin(sprintf('%s.theater', $aliasShowingTheaters2), $aliasTheater)
                ->andWhere(sprintf('%s.masterCode = :theater', $aliasTheater))
                ->setParameter('theater', $theater);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(ShowingTheater::class, 'theater', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }
}
