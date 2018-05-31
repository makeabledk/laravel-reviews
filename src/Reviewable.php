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
        return $this->morphMany(get_class(app(Review::class)), 'reviewable')->withScore();
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithReviewsCount($query)
    {
        return $query->withCount('reviews');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithReviewsScore($query)
    {
        return (new ScoreInteraction($this))->subSelectScoreForRelatedReviews('reviews_score', $this->reviews(), $query);
    }

    /**
     * @return mixed
     */
    public function getReviewsScoreAttribute()
    {
        return (new ScoreInteraction($this))->getOrLoadScoreAttribute('reviews_score');
    }
}
