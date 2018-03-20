<?php

namespace Makeable\LaravelReviews;

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
}
