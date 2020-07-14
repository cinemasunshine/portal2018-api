<?php

declare(strict_types=1);

namespace App\Doctrine\Repositories;

use App\Doctrine\Entities\Schedule;
use App\Doctrine\Entities\ShowingTheater;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

class ScheduleRepository extends EntityRepository
{
    public const PUBLIC_TYPE_NOW_SHOWING = 'now-showing';
    public const PUBLIC_TYPE_COMING_SOON = 'coming-soon';

    /**
     * Add active query
     *
     * @param QueryBuilder $qb
     * @param string $alias
     * @return void
     */
    protected function addActiveQuery(QueryBuilder $qb, string $alias)
    {
        $qb->andWhere(sprintf('%s.isDeleted = false', $alias));
    }

    /**
     * Add public query
     *
     * @param QueryBuilder $qb
     * @param string $alias
     * @return void
     */
    protected function addPublicQuery(QueryBuilder $qb, string $alias)
    {
        $this->addActiveQuery($qb, $alias);

        $qb
            ->andWhere(sprintf('%s.publicStartDt <= CURRENT_TIMESTAMP()', $alias))
            ->andWhere(sprintf('%s.publicEndDt > CURRENT_TIMESTAMP()', $alias));
    }

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
            ->innerJoin(sprintf('%s.image', $aliasTitle), $aliasTitleImage);

        $aliasShowingFormats = 'sf';
        $qb
            ->addSelect($aliasShowingFormats)
            ->innerJoin(sprintf('%s.showingFormats', $alias), $aliasShowingFormats);

        $aliasShowingTheaters = 'st';
        $qb
            ->addSelect($aliasShowingTheaters)
            ->innerJoin(sprintf('%s.showingTheaters', $alias), $aliasShowingTheaters);

        $this->addPublicQuery($qb, $alias);

        $qb
            ->where(sprintf('%s.id = :id', $alias))
            ->setParameter('id', $id);

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
            ->innerJoin(sprintf('%s.image', $aliasTitle), $aliasTitleImage);

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

    /**
     * Add now showing query
     *
     * @param QueryBuilder $qb
     * @param string $alias
     * @return void
     */
    protected function addNowShowingQuery(QueryBuilder $qb, string $alias)
    {
        $this->addPublicQuery($qb, $alias);

        $qb
            ->andWhere(sprintf('%s.startDate <= CURRENT_DATE()', $alias))
            ->orderBy(sprintf('%s.startDate', $alias), 'DESC');
    }

    /**
     * Add coming soon query
     *
     * @param QueryBuilder $qb
     * @param string $alias
     * @return void
     */
    protected function addComingSoonQuery(QueryBuilder $qb, string $alias)
    {
        $this->addPublicQuery($qb, $alias);

        $qb
            ->andWhere(sprintf('%s.startDate > CURRENT_DATE()', $alias))
            ->orderBy(sprintf('%s.startDate', $alias), 'ASC');
    }
}
