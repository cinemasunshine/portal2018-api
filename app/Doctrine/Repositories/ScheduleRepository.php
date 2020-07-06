<?php

declare(strict_types=1);

namespace App\Doctrine\Repositories;

use App\Doctrine\Entities\Schedule;
use Doctrine\ORM\EntityRepository;
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
        $qb
            ->andWhere(sprintf('%s.isDeleted = false', $alias))
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
        $qb
            ->where(sprintf('%s.id = :id', $alias))
            ->setParameter('id', $id);

        $this->addActiveQuery($qb, $alias);

        return $qb->getQuery()->getSingleResult();
    }
}
