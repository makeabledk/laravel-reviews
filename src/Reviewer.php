<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reviewer
{
//    use HasScore;
//
//    /**
//     * @return MorphMany
//     */
//    public function authoredReviews()
//    {
//        return $this->morphMany(Review::class, 'reviewer');
//    }
//
//
//    /**
//     * @param Builder $query
//     * @return Builder
//     */
//    public function scopeWithAuthoredScore($query)
//    {
//        return $this->addSubSelect('authored_score', $this->selectScoreForRelatedReviews($this->authoredReviews()), $query);
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getAuthoredScoreAttribute()
//    {
//        return $this->getOrLoadScoreAttribute('authored_score');
//    }
}
