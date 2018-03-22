<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reviewer
{
    /**
     * @return MorphMany
     */
    public function authoredReviews()
    {
        return $this->morphMany(Review::class, 'reviewer');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithAuthoredScore($query)
    {
        return (new ScoreInteraction($this))->subSelectScoreForRelatedReviews('authored_score', $this->authoredReviews(), $query);
    }

    /**
     * @return mixed
     */
    public function getAuthoredScoreAttribute()
    {
        return (new ScoreInteraction($this))->getOrLoadScoreAttribute('authored_score');
    }
}
