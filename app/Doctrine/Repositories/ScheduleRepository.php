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
     * Find now showing
     *
     * @param string|null $theater
     * @return Schedule[]
     */
    public function findNowShowing(?string $theater = null): array
    {
        $alias = 's';
        $qb = $this->createQueryBuilder($alias);

        $this->addNowShowingQuery($qb, $alias);

        if ($theater) {
            $aliasShowingTheaters = 'st';
            $aliasTheater = 't';
            $qb
                ->innerJoin(sprintf('%s.showingTheaters', $alias), $aliasShowingTheaters)
                ->innerJoin(sprintf('%s.theater', $aliasShowingTheaters), $aliasTheater)
                ->andWhere(sprintf('%s.masterCode = :theater', $aliasTheater))
                ->setParameter('theater', $theater);
        }

        return $qb->getQuery()->getResult();
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
     * Find coming soon
     *
     * @param string|null $theater
     * @return Schedule[]
     */
    public function findComingSoon(?string $theater = null): array
    {
        $alias = 's';
        $qb = $this->createQueryBuilder($alias);

        $this->addComingSoonQuery($qb, $alias);

        if ($theater) {
            $qb
                ->innerJoin(sprintf('%s.showingTheaters', $alias), 'st')
                ->innerJoin(sprintf('%s.theater', 'st'), 't')
                ->andWhere(sprintf('%s.masterCode = :theater', 't'))
                ->setParameter('theater', $theater);
        }

        return $qb->getQuery()->getResult();
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
