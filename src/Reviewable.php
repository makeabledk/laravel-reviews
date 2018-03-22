<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reviewable
{
    /**
     * @return MorphMany
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithScore($query)
    {
        return (new ScoreInteraction($this))->subSelectScoreForRelatedReviews('score', $this->reviews(), $query);
    }

    /**
     * @return mixed
     */
    public function getScoreAttribute()
    {
        return (new ScoreInteraction($this))->getOrLoadScoreAttribute('score');
    }
}
