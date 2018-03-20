<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reviewee
{
    use HasRatingThroughReviews;

    /**
     * @param Review $review
     * @param Model $reviewable
     * @param Model $reviewer
     * @return Review
     */
    public function review($review, $reviewable = null, $reviewer = null)
    {
        if ($reviewable) {
            $review->reviewable()->associate($reviewable);
        }

        if ($reviewer) {
            $review->reviewer()->associate($reviewer);
        }

        return $this->reviews()->save($review);
    }

    /**
     * @return MorphMany
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewee');
    }
}