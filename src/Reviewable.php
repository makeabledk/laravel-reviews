<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reviewable
{
    use HasRatingThroughReviews;

    /**
     * @return MorphMany
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}