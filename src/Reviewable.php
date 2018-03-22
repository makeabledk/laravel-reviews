<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reviewable
{
    use HasScore;

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
        return $this->addSubSelect('score', $this->selectScoreForRelatedReviews($this->reviews()), $query);
    }

    /**
     * @return mixed
     */
    public function getScoreAttribute()
    {
        return $this->getOrLoadScoreAttribute('score');
    }
}
