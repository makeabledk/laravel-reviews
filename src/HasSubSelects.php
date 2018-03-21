<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;

trait HasSubSelects
{
    /**
     * @param $column
     * @param Builder $subQuery
     * @param Builder $mainQuery
     * @return Builder
     */
    protected function addSubSelect($column, $subQuery, $mainQuery)
    {
        if ($mainQuery->getQuery()->columns === null) {
            $mainQuery->select($mainQuery->getQuery()->from.'.*');
        }

        return $mainQuery->selectSub($subQuery->limit(1)->getQuery(), $column);
    }
}
