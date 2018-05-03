<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait Reviewee
{
    /**
     * @param Review $review
     * @param Collection|array $ratings
     * @return Review
     */
    public function review($review, $ratings)
    {
        $review->reviewee()->associate($this)->save();
        $review->ratings()->saveMany(
            collect($ratings)->map(function ($rating) {
                return is_array($rating) ? new Rating($rating) : $rating;
            })
        );

        return $review;
    }

    /**
     * @return MorphMany
     */
    public function reviews()
    {
        return $this->morphMany(get_class(app(Review::class)), 'reviewee')->withScore();
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
