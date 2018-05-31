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
        return $this->morphMany(get_class(app(Review::class)), 'reviewer')->withScore();
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithAuthoredReviewsCount($query)
    {
        return $query->withCount('authoredReviews');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithAuthoredReviewsScore($query)
    {
        return (new ScoreInteraction($this))->subSelectScoreForRelatedReviews('authored_reviews_score', $this->authoredReviews(), $query);
    }

    /**
     * @return mixed
     */
    public function getAuthoredReviewsScoreAttribute()
    {
        return (new ScoreInteraction($this))->getOrLoadScoreAttribute('authored_reviews_score');
    }
}
