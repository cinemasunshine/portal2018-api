<?php

declare(strict_types=1);

namespace App\Doctrine\Repositories;

use App\Doctrine\Entities\Schedule;
use App\Doctrine\Entities\ShowingTheater;
use Cinemasunshine\ORM\Repositories\ScheduleRepository as BaseRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class ScheduleRepository extends BaseRepository
{
    public const PUBLIC_TYPE_NOW_SHOWING = 'now-showing';
    public const PUBLIC_TYPE_COMING_SOON = 'coming-soon';

    /**
     * Find one active schedule
     *
     * @param integer $id
     * @return Schedule
     */
    public function findOneActive($id)
    {
        $alias = 's';
        $qb = $this->createQueryBuilder($alias);

        $aliasTitle = 't';
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

        $aliasShowingTheaters = 'st';
        $qb
            ->addSelect($aliasShowingTheaters)
            ->innerJoin(sprintf('%s.showingTheaters', $alias), $aliasShowingTheaters);

        $qb
            ->where(sprintf('%s.id = :id', $alias))
            ->setParameter('id', $id);

        $this->addPublicQuery($qb, $alias);

        $query = $qb->getQuery();
        $query->setFetchMode(ShowingTheater::class, 'theater', ClassMetadata::FETCH_EAGER);

        return $query->getSingleResult();
    }

    /**
     * @param string $type
     * @param string|null $theater
     * @return Schedule[]
     */
    public function findPublic(string $type, ?string $theater = null): array
    {
        if (
            !in_array($type, [
                self::PUBLIC_TYPE_NOW_SHOWING,
                self::PUBLIC_TYPE_COMING_SOON,
            ])
        ) {
            throw new \InvalidArgumentException('Invalid "type".');
        }

        $alias = 's';
        $qb = $this->createQueryBuilder($alias);

        $aliasTitle = 't';
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

        $aliasShowingTheaters = 'st';
        $qb
            ->addSelect($aliasShowingTheaters)
            ->innerJoin(sprintf('%s.showingTheaters', $alias), $aliasShowingTheaters);

        if ($type === self::PUBLIC_TYPE_NOW_SHOWING) {
            $this->addNowShowingQuery($qb, $alias);
        } elseif ($type === self::PUBLIC_TYPE_COMING_SOON) {
            $this->addComingSoonQuery($qb, $alias);
        }

        if ($theater) {
            $aliasTheater = 'th';
            $qb
                ->innerJoin(sprintf('%s.theater', $aliasShowingTheaters), $aliasTheater)
                ->andWhere(sprintf('%s.masterCode = :theater', $aliasTheater))
                ->setParameter('theater', $theater);
        }

        $query = $qb->getQuery();
        $query->setFetchMode(ShowingTheater::class, 'theater', ClassMetadata::FETCH_EAGER);

        return $query->getResult();
    }
}
