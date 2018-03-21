<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait Reviewee
{
    use HasScore,
        HasSubSelects;

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
        return $this->morphMany(Review::class, 'reviewee');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithScore($query)
    {
        return $this->addSubSelect('score',
            Rating::combinedScore()
                ->leftJoin('reviews', 'ratings.review_id', '=', 'reviews.id')
                ->where('reviews.reviewee_type', $this->getMorphClass())
                ->whereRaw('reviews.reviewee_id', $this->getKey()),
            $query);
    }
}
